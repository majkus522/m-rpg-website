<?php
    switch($requestMethod)
    {
        case "GET":
            $query = 'select `id`, `username`, `email` from `players` where ';
            if(isset($_GET["username"]))
                $query = $query . '`username` = "' . $_GET["username"] . '"';
            else if(isset($_GET["email"]))
                $query = $query . '`email` = "' . $_GET["email"] . '"';
            else
                exitApi(400, "Enter username or email");
            $queryResult = connectToDatabase($query . ' limit 1');
            if(empty($queryResult))
                exitApi(404, "Player doesn't exists");
            $queryResult = $queryResult[0];
            $code = generateCode($queryResult->id);
            $query = 'insert into `password-recovery` (`player`, `code`, `date`) values (' . $queryResult->id . ', "' . $code . '", now())';
            connectToDatabase($query);
            $message = file_get_contents("messages/password-recovery.html");
            $message = str_replace("$[username]", $queryResult->username, $message);
            $message = str_replace("$[code]", $code, $message);
            require "mailer.php";
            sendMail($queryResult->email, "M-RPG Password recovery", $message);
            break;

        case "PATCH":
            if(isSingleGet())
            {
                $header = getHeader("Password");
                if($header != false)
                {
                    $query = 'select `player` from `password-recovery` where `code` = "' . $requestUrlPart[$urlIndex + 1] . '" and hour(timediff(now(), `date`)) < 24 limit 1';
                    $queryResult = connectToDatabase($query);
                    if(empty($queryResult))
                    {
                        connectToDatabase('delete from `password-recovery` where `code` = "' . $requestUrlPart[$urlIndex + 1] . '"');
                        exitApi(400, "Link expired");
                    }
                    $passTest = validPassword($header);
                    if($passTest === true)
                    {
                        $password = encode(password_hash(base64_decode($header), PASSWORD_DEFAULT));
                        $query = 'update `players` set `password` = "' . $password . '" where `id` = ' . $queryResult[0]->player;
                        connectToDatabase($query);
                        $query = 'delete from `password-recovery` where `player` = ' . $queryResult[0]->player;
                        connectToDatabase($query);
                    }
                    else
                    {
                        exitApi(400, $passTest);
                    }
                }
                else
                    exitApi(400, "Enter password");
            }
            else
                exitApi(400, "Enter code");
            break;

        default:
            exitApi(501, "Method not implemented");
            return;
    }
?>