<?php
    switch($requestMethod)
    {
        case "GET":
            $query = 'select `id`, `username`, `email` from `players` where ';
            if(isset($_GET["username"]))
                $query .= '`username` = "' . $_GET["username"] . '"';
            else if(isset($_GET["email"]))
                $query .= '`email` = "' . $_GET["email"] . '"';
            else
                exitApi(400, "Enter username or email");
            $queryResult = connectToDatabase($query . ' limit 1');
            if(empty($queryResult))
                exitApi(404, "Player doesn't exists");
            $queryResult = $queryResult[0];
            $code = generateCode($queryResult->id);
            $query = 'insert into `password-recovery` (`player`, `code`, `date`) values (?, ?)';
            connectToDatabase($query, ["is", $queryResult->id, $code]);
            $message = file_get_contents("messages/password-recovery.html");
            $message = str_replace("$[username]", $queryResult->username, $message);
            $message = str_replace("$[code]", $code, $message);
            require "mailer.php";
            sendMail($queryResult->email, "M-RPG Password recovery", $message);
            http_response_code(204);
            break;

        case "PATCH":
            if(!isSingleGet())
                exitApi(400, "Enter code");
            $data = json_decode(file_get_contents("php://input"));
            if(!isset($data->password))
                exitApi(400, "Enter password");
            $query = 'select `player` from `password-recovery` where `code` = ? and hour(timediff(now(), `date`)) < 24 limit 1';
            $queryResult = connectToDatabase($query, ["s", $requestUrlPart[$urlIndex + 1]]);
            if(empty($queryResult))
                exitApi(404, "Link expired");
            $password = base64_decode($data->password);
            $passTest = validPassword($password);
            if($passTest !== true)
                exitApi(400, $passTest);
            $password = encode(password_hash($password, PASSWORD_DEFAULT));
            $query = 'update `players` set `password` = ? where `id` = ?';
            connectToDatabase($query, ["si", $password, $queryResult[0]->player]);
            $query = 'delete from `password-recovery` where `player` = ?';
            connectToDatabase($query, ["i", $queryResult[0]->player]);
            http_response_code(204);
            break;

        default:
            exitApi(501, "Method not implemented");
            return;
    }
?>