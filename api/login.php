<?php
    require "../imports/common.php";
    if(!isset($_GET["player"]))
        exitApi(400, "Enter player");
    $queryResult = connectToDatabase('select `password`, `id` from `players` where `username` = ?', array("s", $_GET["player"]));
    if(empty($queryResult))
        exitApi(404, "Player doesn't exists");
    $password = getHeader("Password");
    if($password === false)
        exitApi(400, "Enter password");
    require "hasher.php";
    if(!password_verify(base64_decode($password), decode($queryResult[0]->password)))
        exitApi(401, "Wrong password");
    $key = generateSessionKey($queryResult[0]->id);
    $temp = isset($_GET["temp"]);
    $query = 'insert into `players-sessions` (`key`, `player`';
    if($temp)
        $query .= ', `temp`, `date`';
    $query .= ') values (?, ?';
    if($temp)
        $query .= ', ?, now()';
    $query .= ')';
    $parameters = array($temp ? "sii" : "si", $key, $queryResult[0]->id);
    if($temp)
        array_push($parameters, 1);
    connectToDatabase($query, $parameters);
    echo $key;
?>