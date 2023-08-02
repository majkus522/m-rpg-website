<?php
    require "../imports/common.php";
    switch($_SERVER["REQUEST_METHOD"])
    {
        case "GET":
            if(!(isset($_GET["email"]) || isset($_GET["username"])))
                exitApi(400, "Enter email or username");
            $query = 'select `id`, `username`,`email` from `players` where `' . (isset($_GET["email"]) ? 'email' : 'username') . '` = ? limit 1';
            $queryResult = connectToDatabase($query, array("s", (isset($_GET["email"]) ? $_GET["email"] : $_GET["username"])));
            if(empty($queryResult))
                exitApi(404, "Player doesn't exists");
            require "hasher.php";
            $code = generateCode($queryResult[0]->id);
            $message = file_get_contents("messages/password-recovery.html");
            $message = str_replace("$[code]", $code, $message);
            $message = str_replace("$[username]", $queryResult[0]->username, $message);
            require "mailer.php";
            $query = 'insert into `password-recovery` (`player`, `code`) values (?, ?)';
            connectToDatabase($query, array("is", $queryResult[0]->id, $code));
            break;

        case "PATCH":
            connectToDatabase('delete from `password-recovery` where hour(timediff(now(), `date`)) > 24');
            if(!isset($_GET["code"]))
                exitApi(400, "Enter code");
            $data = json_decode(file_get_contents("php://input"));
            if(!isset($data->password))
                exitApi(400, "Enter new password");
            $query = 'select `player` from `password-recovery` where `code` = ? limit 1';
            $queryResult = connectToDatabase($query, array("s", $_GET["code"]));
            if(empty($queryResult))
                exitApi(400, "Code expired");
            $validPassword = validPassword(base64_decode($data->password));
            if($validPassword !== true)
                exitApi(400, $validPassword);
            $query = 'update `players` set `password` = ?';
            require "hasher.php";
            connectToDatabase($query, array("s", encode(password_hash(base64_decode($data->password), PASSWORD_DEFAULT))));
            connectToDatabase('delete from `password-recovery` where `code` = ?', array("s", $_GET["code"]));
            break;

        default:
            exitApi(501, "Method not implemented");
            break;
    }
?>