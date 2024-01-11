<?php
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
                            $key = getSessionType($headerType) . generateSessionKey($queryResult[0]->id);
                            $query = 'insert into `players-sessions`(`key`, `player`';
                            if($headerTemp)
                                $query .= ', `date`';
                            $query .= ') values (?, ?';
                            if($headerTemp)
                                $query .= ', now()';
                            connectToDatabase('delete from `players-sessions` where `player` = ? and `key` like ?', "is", [$queryResult[0]->id, getSessionType($headerType) . "%"]);
                            connectToDatabase($query . ')', "si", [$key, $queryResult[0]->id]);
                            if($requestMethod != "HEAD")
                                echo json_encode(["key" => $key, "id" => $queryResult[0]->id]);
                            else
                                echo header("Content-Length: " . strlen($key));
                            break;

                        case "session":
                            connectToDatabase('delete from `players-sessions` where `date` is not null and hour(timediff(now(), `date`)) > 24');
                            $headerKey = getHeader("Session-Key");
                            if($headerKey === false)
                                exitApi(400, "Enter session key");
                            $headerId = getHeader("Session-ID");
                            if($headerId === false)
                                exitApi(400, "Enter session id");
                            $queryResult = connectToDatabase('select `username` from `players` where `id` = ? limit 1', "s", [$headerId]);
                            if(empty($queryResult))
                                exitApi(404, "Player doesn't exists");
                            if($requestUrlPart[$urlIndex + 1] != $queryResult[0]->username)
                                exitApi(401, "Incorrect player");
                            if(empty(connectToDatabase('select `players-sessions`.`id` from `players-sessions` where `key` = ? and `player` = ? limit 1', "si", [$headerKey, $headerId])))
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
                $allowedParams = ["level", "money"];
                foreach(json_decode(file_get_contents("data/playerStats.json")) as $element)
                    array_push($allowedParams, $element->short);
                $parameters = [];
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
            echo json_encode($apiResult->content);
            break;

        case "PATCH":
            if(!isSingleGet())
                exitApi(400, "Enter player");
            isPlayerLogged($requestUrlPart[$urlIndex + 1]);
            $vars = get_object_vars(json_decode(file_get_contents("php://input")));
            if(empty($vars))
                exitApi(400, "Enter some changes");
            $query = 'update `players` set';
            $first = true;
            $parameters = [];
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
            http_response_code(204);
            break;

        case "DELETE":
            if(isSingleGet())
            {
                isPlayerLogged($requestUrlPart[$urlIndex + 1]);
                $queryResult = connectToDatabase('select `guild` from `players` where `username` = ?', "s", [$requestUrlPart[$urlIndex + 1]]);
                $guild = $queryResult[0]->guild;
                connectToDatabase('delete from `players` where `username` = ?', "s", [$requestUrlPart[$urlIndex + 1]]);
                if($guild != null)
                    connectToDatabase('update `guilds` set `leader` = (select `id` from `players` where `guild` = ? limit 1)', "s", [$guild]);
            }
            else
                exitApi(400, "Enter player");
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