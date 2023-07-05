<?php
    switch($requestMethod)
    {
        case "GET":
            if(isSingleGet())
            {
                if(isset($requestUrlPart[$urlIndex + 2]))
                {
                    switch(strtolower($requestUrlPart[$urlIndex + 2]))
                    {
                        case "logged":
                            $header = getHeader("Password");
                            if($header === false)
                                exitApi(400, "Enter player password");
                            $query = 'select `password` from `players` where `username` = "' . $requestUrlPart[$urlIndex + 1] . '"';
                            $queryResult = connectToDatabase($query);
                            if(empty($queryResult))
                                exitApi(404, "Player doesn't exists");
                            if(!password_verify(base64_decode($header), decode($queryResult[0]->password)))
                                exitApi(401, "Wrong password");
                            break;
                    }
                }
                else
                {
                    $header = getHeader("Password");
                    if($header != false)
                    {
                        require "playerLogged.php";
                        $query = 'select * from `players` where `username` = "' . $requestUrlPart[$urlIndex + 1] . '" limit 1';
                    }
                    else
                    {
                        $query = 'select `id`, `username` from `players` where `username` = "' . $requestUrlPart[$urlIndex + 1] . '" limit 1';
                        http_response_code(206);
                    }
                    $queryResult = connectToDatabase($query);
                    if(empty($queryResult))
                        exitApi(404, "Player doesn't exists");
                    echo json_encode($queryResult[0]);
                }
            }
            else
            {
                $query = 'select `id`, `username` from `players` where 1 = 1';
                $order = "";
                foreach($_GET as $key => $value)
                {
                    switch($key)
                    {
                        case "minLevel":
                            if(!is_numeric($value) || $value < 1)
                                exitApi(400, "Incorect query string (minLevel)");
                            $query .= ' and `level` >= ' . $value;
                            break;

                        case "maxLevel":
                            if(!is_numeric($value) || $value < 1)
                                exitApi(400, "Incorect query string (maxLevel)");
                            $query .= ' and `level` <= ' . $value;
                            break;

                        case "minMoney":
                            if(!is_numeric($value) || $value < 0)
                                exitApi(400, "Incorect query string (minMoney)");
                            $query .= ' and `money` >= ' . $value;
                            break;

                        case "maxMoney":
                            if(!is_numeric($value) || $value < 0)
                                exitApi(400, "Incorect query string (maxMoney)");
                            $query .= ' and `level` <= ' . $value;
                            break;

                        case "order":
                            if($order == "")
                            {
                                switch($value)
                                {
                                    case "level-desc":
                                        $order = ' order by `level` desc';
                                        break;
    
                                    case "level":
                                        $order = ' order by `level` asc';
                                        break;
                                }
                            }
                            break;
                    }
                }
                require "headerItems.php";
                $query .= $order . ' limit ' . $limit . ' offset ' . $offset;
                $queryResult = connectToDatabase($query);
                if(empty($queryResult))
                    exitApi(404, "Can't find any player matching conditions");
                header("Items-Count: " . sizeof($queryResult));
                http_response_code(206);
                echo json_encode($queryResult);
            }
            break;

        case "POST":
            $data = json_decode(file_get_contents("php://input"));
            $validUsername = validUsername($data->username);
            if($validUsername !== true)
                exitApi(400, $validUsername);
            $validEmail = validEmail($data->email);
            if($validEmail !== true)
                exitApi(400, $validEmail);
            $validPassword = validPassword($data->password);
            if($validPassword !== true)
                exitApi(400, $validPassword);
            $query = 'select `id` from `players` where `username` = "' . $data->username . '"';
            if(!empty(connectToDatabase($query)))
                exitApi(400, "Player already exists");
            $query = 'select `id` from `players` where `email` = "' . $data->email . '"';
            if(!empty(connectToDatabase($query)))
                exitApi(400, "Email already taken");
            $query = 'insert into `players`(`username`, `email`, `password`) values ("' . $data->username . '", "' . $data->email . '", "' . encode(password_hash(base64_decode($data->password), PASSWORD_DEFAULT)) . '")';
            connectToDatabase($query);
            http_response_code(201);
            break;

        case "PATCH":
            if(isSingleGet())
            {
                require "playerLogged.php";
                $vars = get_object_vars(json_decode(file_get_contents("php://input")));
                if(empty($vars))
                    exitApi(400, "Enter some changes");
                $query = 'update `players` set';
                $first = true;
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
                            $query .= ' `' . $key . '` = "' . $value . '"';
                            $first = false;
                            break;

                        case "level":
                            if($value < 1)
                                exitApi(400, "Level : incorect value");
                            if(!$first)
                                $query .= ',';
                            $query .= ' `' . $key . '` = ' . $value;
                            $first = false;
                            break;

                        case "exp":
                            if($value < 0)
                                exitApi(400, "Exp : incorect value");
                            if(!$first)
                                $query .= ',';
                            $query .= ' `' . $key . '` = ' . $value;
                            $first = false;
                            break;

                        case "password":
                            $password = base64_decode($value);
                            $valid = validPassword($password);
                            if($valid !== true)
                                exitApi(400, $valid);
                            if(!$first)
                                $query .= ',';
                            $query .= ' `' . $key . '` = "' . encode(password_hash($password, PASSWORD_DEFAULT)) . '"';
                            $first = false;
                            break;
                    }
                }
                $query .= ' where `username` = "' . $requestUrlPart[$urlIndex + 1] . '"';
                connectToDatabase($query);
            }
            else
                exitApi(400, "Specify player");
            break;

        case "DELETE":
            if(isSingleGet())
            {
                require "playerLogged.php";
                connectToDatabase('delete from `players` where `username` = "' . $requestUrlPart[$urlIndex + 1] . '"');
            }
            else
                exitApi(400, "Specify player");
            break;

        case "OPTIONS":
            echo json_encode([
                "GET /api/endpoints/players = select all players (hidden data)",
                "   Items: {offset(optional)}-{limit} = specify number and offset of selected players (default[Items: 0-50])",
                "GET /api/endpoints/players/{username} = show specified player data",
                "   Passowrd: {password} = send player password to get acces to full player data",
                "GET /api/endpoints/players/{username}/logged = check if player is logged",
                "   (required)Passowrd: {password} = send player password"
            ]);
            break;

        default:
            exitApi(501, "Method not implemented");
            return;
    }
?>