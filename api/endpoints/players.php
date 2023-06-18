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
                            if($header != false)
                            {
                                $query = 'select `password` from `players` where `username` = "' . $requestUrlPart[$urlIndex + 1] . '" limit 1';
                                $queryResult = connectToDatabase($query);
                                if(empty($queryResult))
                                    exitApi(404, "Player doesn't exists");
                                echo json_encode(password_verify(base64_decode($header), decode($queryResult[0]->password)) ? 1 : 0);
                            }
                            else
                                exitApi(400, "Specify Passsword header");
                            break;
                    }
                }
                else
                {
                    $header = getHeader("Password");
                    if($header != false)
                    {
                        $query = 'select `password` from `players` where `username` = "' . $requestUrlPart[$urlIndex + 1] . '" limit 1';
                        $queryResult = connectToDatabase($query);
                        if(empty($queryResult))
                            exitApi(404, "Player doesn't exists");
                        if(password_verify(base64_decode($header), decode($queryResult[0]->password)))
                            $query = 'select * from `players` where `username` = "' . $requestUrlPart[$urlIndex + 1] . '" limit 1';
                        else
                            exitApi(401, "Wrong password");
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
                $header = getHeader("Items");
                $limit = 50;
                $offset = 0;
                if($header != false)
                {
                    if(str_contains($header, "-"))
                    {
                        $part = explode("-", $header);
                        $offset = (int)$part[0];
                        $limit = (int)$part[1];
                    }
                    else
                        $limit = (int)$header;
                }
                $query = 'select `id`, `username` from `players` limit ' . $limit . ' offset ' . $offset;
                $queryResult = connectToDatabase($query);
                if(empty($queryResult))
                    exitApi(404, "Can't find any player matching conditions");
                header("Items-Count: " . sizeof($queryResult));
                http_response_code(206);
                echo json_encode($queryResult);
            }
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