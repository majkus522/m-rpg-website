<?php
    if($_SERVER["REQUEST_METHOD"] == "PATCH" || $_SERVER["REQUEST_METHOD"] == "DELETE")
    {
        require "../imports/loginCheck.php";
        if(!$validLogin)
            exitApi(401, "Login first");
    }
    else
        require "../imports/common.php";
    require "hasher.php";
    switch($_SERVER["REQUEST_METHOD"])
    {
        case "POST":
            $data = json_decode(file_get_contents("php://input"));
            if(!isset($data->username))
                exitApi(400, "Enter username");
            if(!isset($data->email))
                exitApi(400, "Enter email");
            if(!isset($data->password))
                exitApi(400, "Enter password");

            $validPassword = validPassword(base64_decode($data->password));
            if($validPassword !== true)
                exitApi(400, $validPassword);
            $validEmail = validEmail($data->email);
            if($validEmail !== true)
                exitApi(400, $validEmail);
            $validUsername = validUsername($data->username);
            if($validUsername !== true)
                exitApi(400, $validUsername);
            $query = 'select `id` from `players` where `username` = ?';
            if(!empty(connectToDatabase($query, array("s", $data->username))))
                exitApi(400, "Player already exists");
            $query = 'select `id` from `players` where `email` = ?';
            if(!empty(connectToDatabase($query, array("s", $data->email))))
                exitApi(400, "Email already taken");
            $query = 'insert into `players` (`username`, `email`, `password`) values (?, ?, ?)';
            $parameters = array("sss", $data->username, $data->email, encode(password_hash(base64_decode($data->password), PASSWORD_DEFAULT)));
            connectToDatabase($query, $parameters);
            http_response_code(201);
            break;

        case "PATCH":
            $data = json_decode(file_get_contents("php://input"));
            if(!isset($data->password) && !isset($data->email))
                exitApi(400, "Enter some changes");
            $query = 'update `players` set';
            $parameters = [];
            $types = "";
            if(isset($data->password))
            {
                $validPassword = validPassword(base64_decode($data->password));
                if($validPassword !== true)
                    exitApi(400, $validPassword);
                $query .= ' `password` = ?';
                array_push($parameters, encode(password_hash(base64_decode($data->password), PASSWORD_DEFAULT)));
                $types .= "s";
            }
            if(isset($data->email))
            {
                $validEmail = validEmail($data->email);
                if($validEmail !== true)
                    exitApi(400, $validEmail);
                if(isset($data->password))
                    $query .= ', ';
                $query .= ' `email` = ?';
                array_push($parameters, $data->email);
                $types .= "s";
            }
            $parameters = array_merge([$types], $parameters);
            connectToDatabase($query, $parameters);
            break;

        case "DELETE":
            $query = 'delete from `players` where `username` = ?';
            connectToDatabase($query, array("s", $_COOKIE["username"]));
            break;
    }
?>