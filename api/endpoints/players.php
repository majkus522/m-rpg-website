<?php
    require "playerLogged.php";

    switch($requestMethod)
    {
        case "GET":
        case "HEAD":
            if(isSingleGet())
            {
                if(isset($requestUrlPart[$urlIndex + 2]))
                {
                    switch(strtolower($requestUrlPart[$urlIndex + 2]))
                    {
                        case "login":
                            $headerPassword = getHeader("Password");
                            if($headerPassword === false)
                                exitApi(400, "Enter player password");
                            $headerType = getHeader("Session-Type");
                            if($headerType === false)
                                exitApi(400, "Enter session type");
                            $query = 'select `password`, `id` from `players` where `username` = ? limit 1';
                            $queryResult = connectToDatabase($query, "s", [$requestUrlPart[$urlIndex + 1]]);
                            if(empty($queryResult))
                                exitApi(404, "Player doesn't exists");
                            if(!password_verify(base64_decode($headerPassword), decode($queryResult[0]->password)))
                                exitApi(401, "Wrong password");
                            $headerTemp = filter_var(getHeader("Temp"), FILTER_VALIDATE_BOOLEAN);
                            $key = generateSessionKey($queryResult[0]->id, $headerType);
                            $query = 'insert into `players-sessions`(`key`, `player`, `type`';
                            if($headerTemp)
                                $query .= ', `temp`, `date`';
                            $query .= ') values (?, ?, ?';
                            if($headerTemp)
                                $query .= ', 1, now()';
                            connectToDatabase('delete from `players-sessions` where `player` = ? and `type` = ?', "is", [$queryResult[0]->id, $headerType]);
                            connectToDatabase($query . ')', "sis", [$key, $queryResult[0]->id, $headerType]);
                            if($requestMethod != "HEAD")
                                echo $key;
                            else
                                echo header("Content-Length: " . strlen($key));
                            break;

                        case "session":
                            connectToDatabase('delete from `players-sessions` where `temp` and hour(timediff(now(), `date`)) > 24');
                            $headerKey = getHeader("Session-Key");
                            if($headerKey === false)
                                exitApi(400, "Enter player session");
                            $headerType = getHeader("Session-Type");
                            if($headerType === false)
                                exitApi(400, "Enter session type");
                            $query = 'select `id` from `players` where `username` = ? limit 1';
                            $queryResult = connectToDatabase($query, "s", [$requestUrlPart[$urlIndex + 1]]);
                            if(empty($queryResult))
                                exitApi(404, "Player doesn't exists");
                            $query = 'select `players-sessions`.`id` from `players-sessions` where `type` = ? and `key` = ? and `player` = ? limit 1';
                            if(empty(connectToDatabase($query, "ssi", [$headerType, $headerKey, $queryResult[0]->id])))
                                exitApi(401, "Incorrect session key");
                            http_response_code(204);
                            break;

                        default:
                            exitApi(400, "Unknown option");
                            break;
                    }
                }
                else
                {
                    $login = isPlayerLogged($requestUrlPart[$urlIndex + 1], false);
                    $query = 'select ';
                    if($login === true)
                        $query .= '*';
                    else
                    {
                        $query .= '`id`, `username`';
                        http_response_code(206);
                    }
                    $queryResult = connectToDatabase($query . ' from `view-players` where `username` = ? limit 1', "s", [$requestUrlPart[$urlIndex + 1]]);
                    if(empty($queryResult))
                        exitApi(404, "Player doesn't exists");
                    if($requestMethod != "HEAD")
                        echo json_encode($queryResult[0]);
                    else
                        echo header("Content-Length: " . strlen(json_encode($queryResult[0])));
                }
            }
            else
            {
                $query = 'select `id`, `username` from `view-players` where 1 = 1';
                $order = "";
                $allowedParams = array("level", "money");
                foreach(json_decode(file_get_contents("data/playerStats.json")) as $element)
                    array_push($allowedParams, $element->short);
                $parameters = array();
                $types = "";
                foreach($_GET as $key => $value)
                {
                    switch($key)
                    {
                        default:
                            $unknown = true;
                            foreach($allowedParams as $element)
                            {
                                if($key == ("min" . ucfirst($element)))
                                {
                                    if(!is_numeric($value) || $value < ($element == "level" ? 1 : 0))
                                        exitApi(400, "Incorrect query string (min" . ucfirst($element) . ")");
                                    $types .= "i";
                                    $query .= ' and `' . $element . '` >= ?';
                                    array_push($parameters, $value);
                                    $unknown = false;
                                    break;
                                }
                                else if($key == ("max" . ucfirst($element)))
                                {
                                    if(!is_numeric($value) || $value < ($element == "level" ? 1 : 0))
                                        exitApi(400, "Incorrect query string (max" . ucfirst($element) . ")");
                                    $types .= "i";
                                    $query .= ' and `' . $element . '` <= ?';
                                    array_push($parameters, $value);
                                    $unknown = false;
                                    break;
                                }
                            }
                            if($unknown)
                                exitApi(400, "Unknown query string parameter $key");
                            break;

                        case "order":
                            if($order == "")
                            {
                                $unknown = true;
                                foreach($allowedParams as $element)
                                {
                                    if($value == $element)
                                    {
                                        $order = ' order by `' . $element . '` asc';
                                        $unknown = false;
                                        break;
                                    }
                                    else if($value == ($element . "-desc"))
                                    {
                                        $order = ' order by `' . $element . '` desc';
                                        $unknown = false;
                                        break;
                                    }
                                }
                                if($unknown)
                                    exitApi(400, "Unknown order parameter $value");
                            }
                            break;

                        case "search":
                            $query .= ' and LOWER(`username`) like ?';
                            array_push($parameters, "%" . strtolower($value) . "%");
                            $types .= "s";
                            break;

                        case "clazz":
                            if($value == "none")
                                $query .= ' and `clazz` is null';
                            else
                            {
                                $query .= ' and `clazz` = ?';
                                array_push($parameters, strtolower($value));
                                $types .= "s";
                            }
                            break;
                    }
                }
                require "headerItems.php";
                array_push($parameters, $limit, $offset);
                $types .= "ii";
                $query .= $order . ' limit ? offset ?';
                $queryResult = connectToDatabase($query, $types, $parameters);
                if(empty($queryResult))
                    exitApi(404, "Can't find any player matching conditions");
                header("Return-Count: " . sizeof($queryResult));
                http_response_code(206);
                if($requestMethod != "HEAD")
                    echo json_encode($queryResult);
                else
                    header("Content-Length: " . strlen(json_encode($queryResult)));
            }
            break;

        case "POST":
            $data = json_decode(file_get_contents("php://input"));
            if(!isset($data->username))
                exitApi(400, "Enter username");
            if(!isset($data->email))
                exitApi(400, "Enter email");
            if(!isset($data->password))
                exitApi(400, "Enter password");
            $validUsername = validUsername($data->username);
            if($validUsername !== true)
                exitApi(400, $validUsername);
            $validEmail = validEmail($data->email);
            if($validEmail !== true)
                exitApi(400, $validEmail);
            $validPassword = validPassword(base64_decode($data->password));
            if($validPassword !== true)
                exitApi(400, $validPassword);
            $query = 'select `id` from `players` where `username` = ?';
            if(!empty(connectToDatabase($query, "s", [$data->username])))
                exitApi(400, "Player already exists");
            $query = 'select `id` from `players` where `email` = ?';
            if(!empty(connectToDatabase($query, "s", [$data->email])))
                exitApi(400, "Email already taken");
            $query = 'insert into `players`(`username`, `email`, `password`) values (?, ?, ?)';
            connectToDatabase($query, "sss", [$data->username, $data->email, encode(password_hash(base64_decode($data->password), PASSWORD_DEFAULT))]);
            http_response_code(201);

            $apiResult = callApi("players/{$data->username}/login", "GET", ["Password: $data->password", "Session-Type: website"]);
            echo $apiResult->content;
            break;

        case "PATCH":
            if(!isSingleGet())
                exitApi(400, "Enter player");
            isPlayerLogged($requestUrlPart[$urlIndex + 1]);
            if(isset($requestUrlPart[$urlIndex + 2]))
            {
                switch($requestUrlPart[$urlIndex + 2])
                {
                    case "leave":
                        if(empty(connectToDatabase('select `guild` from `players` where `username` = ? and `guild` is not null limit 1', "s", [$requestUrlPart[$urlIndex + 1]])))
                            exitApi(400, "You are not part of any guild");
                        connectToDatabase('update `players` set `guild` = null where `username` = ?', "s", [$requestUrlPart[$urlIndex + 1]]);
                        break;

                    default:
                        exitApi(400, "Unknown option");
                        break;
                }
            }
            else
            {
                $vars = get_object_vars(json_decode(file_get_contents("php://input")));
                if(empty($vars))
                    exitApi(400, "Enter some changes");
                $query = 'update `players` set';
                $first = true;
                $parameters = array();
                $types = "";
                foreach($vars as $key => $value)
                {
                    switch($key)
                    {
                        case "email":
                            $valid = validEmail($value);
                            if($valid !== true)
                                exitApi(400, $valid);
                            if(!empty(connectToDatabase('select `id` from `players` where `email` = "' . $value . '"')))
                                exitApi(400, "Email already taken");
                            if(!$first)
                                $query .= ',';
                            $query .= ' `email` = ?';
                            $first = false;
                            $types .= "s";
                            array_push($parameters, $value);
                            break;

                        case "password":
                            $password = base64_decode($value);
                            $valid = validPassword($password);
                            if($valid !== true)
                                exitApi(400, $valid);
                            if(!$first)
                                $query .= ',';
                            $query .= ' `password` = ?';
                            $first = false;
                            $types .= "s";
                            array_push($parameters, encode(password_hash($password, PASSWORD_DEFAULT)));
                            break;
                    }
                }
                $query .= ' where `username` = ?';
                connectToDatabase($query, $types . "s", array_merge($parameters, [$requestUrlPart[$urlIndex + 1]]));
            }
            http_response_code(204);
            break;

        case "DELETE":
            if(isSingleGet())
            {
                isPlayerLogged($requestUrlPart[$urlIndex + 1]);
                connectToDatabase('delete from `players` where `username` = ?', "s", [$requestUrlPart[$urlIndex + 1]]);
            }
            else
                exitApi(400, "Specify player");
            http_response_code(204);
            break;

        case "OPTIONS":
            echo json_encode(["available-endpoints" => [
                'GET /players',
                'GET /players/{$username}',
                'GET /players/{$username}/login',
                'GET /players/{$username}/session',
                'POST /players',
                'PATCH /players/{$username}',
                'PATCH /players/{$username}/leave',
                'DELETE /players/{$username}'
            ]]);
            break;

        default:
            exitApi(501, "Method not implemented");
            return;
    }
?>