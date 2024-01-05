<?php
    switch($requestMethod)
    {
        case "GET":
        case "HEAD":
            if(isSingleGet())
            {
                $queryResult = connectToDatabase('select * from `guilds` where `slug` = ?', "s", [$requestUrlPart[$urlIndex + 1]]);
                if(empty($queryResult))
                    exitApi(404, "Guild doesn't exists");
                if(isset($requestUrlPart[$urlIndex + 2]))
                {
                    switch($requestUrlPart[$urlIndex + 2])
                    {
                        case "members":
                            $headerKey = getHeader("Session-Key");
                            if($headerKey === false)
                                exitApi(400, "Enter player session key");
                            $player = "";
                            isPlayerLogged($player);
                            if(empty(connectToDatabase('select `players`.`id` from `players` left join `guilds` on `guilds`.`id` = `players`.`guild` where `slug` = ? and `username` = ?', "ss", [$requestUrlPart[$urlIndex + 1], $player])))
                                exitApi(400, "You are not part of this guild");
                            $queryResult = connectToDatabase('select `username` from `guilds` join `players` on `players`.`id` = `guilds`.`leader` where `slug` = ? union select `username` from `guilds` join `players` on `players`.`guild` = `guilds`.`id` where `slug` = ?', "ss", [$requestUrlPart[$urlIndex + 1], $requestUrlPart[$urlIndex + 1]]);
                            $result = [];
                            foreach($queryResult as $element)
                                array_push($result, $element->username);
                            header("Return-Count: " . sizeof($result));
                            break;

                        default:
                            exitApi(400, "Unknown option");
                            break;
                    }
                }
                else
                {
                    $result = $queryResult[0];
                }
            }
            else
            {
                require "headerItems.php";
                $query = 'select * from `guilds` limit ? offset ?';
                $types = "ii";
                $result = connectToDatabase($query, $types, [$limit, $offset]);
                if(empty($result))
                    exitApi(404, "Can't find any guild matching conditions");
                header("Return-Count: " . sizeof($result));
            }
            if($requestMethod == "HEAD")
                header("Content-Length: " . strlen(json_encode($result)));
            else
                echo json_encode($result);
            break;

        case "POST":
            $data = json_decode(file_get_contents("php://input"));
            if(!isset($data->name))
                exitApi(400, "Enter guilds name");
            if(!isset($data->leader))
                exitApi(400, "Enter guilds leader");
            if(strlen($data->name) > 100)
                exitApi(400, "Guilds name is too long");
            isPlayerLogged($data->leader);
            $slug = slugify($data->name);
            if(!empty(connectToDatabase('select `id` from `guilds` where `slug` = ?', "s", [$slug])))
                exitApi(400, "Guild already exists");
            if(empty(connectToDatabase('select `id` from `players` where `username` = ? and `guild` is null', "s", [$data->leader])))
                exitApi(400, "You are already part of guild");
            connectToDatabase('insert into `guilds` (`name`, `slug`, `leader`) values (?, ?, (select `id` from `players` where `username` = ? limit 1))', "sss", [$data->name, $slug, $data->leader], $insertId);
            connectToDatabase('update `players` set `guild` = ? where `username` = ?', "is", [$insertId, $data->leader]);
            http_response_code(201);
            break;

        case "PATCH":
            if(!isSingleGet())
                exitApi(400, "Enter guilds name");
            $queryResult = connectToDatabase('select `guilds`.`id`, `players`.`username` from `guilds` left join `players` on `players`.`id` = `guilds`.`leader` where `slug` = ?', "s", [$requestUrlPart[$urlIndex + 1]]);
            if(empty($queryResult))
                exitApi(404, "Guild doesn't exists");
            $guildData = $queryResult[0];
            isPlayerLogged($guildData->username);
            if(isset($requestUrlPart[$urlIndex + 2]))
            {
                $player = file_get_contents("php://input");
                if(strlen($player) == 0)
                    exitApi(400, "Enter player");
                if(empty(connectToDatabase('select `id` from `players` where `username` = ?', "s", [$player])))
                    exitApi(404, "Player doesn't exists");
                switch($requestUrlPart[$urlIndex + 2])
                {
                    case "add":
                        if(empty(connectToDatabase('select `guild` from `players` where `username` = ? and `guild` is null', "s", [$player])))
                            exitApi(400, "Player is already part of the guild");
                        connectToDatabase('update `players` set `guild` = ? where `username` = ?', "is", [$guildData->id, $player]);
                        break;
    
                    case "kick":
                        if(empty(connectToDatabase('select `id` from `players` where `username` = ? and `guild` = ?', "si", [$player, $guildData->id])))
                            exitApi(400, "Player isn't part of your guild");
                        connectToDatabase('update `players` set `guild` = null where `username` = ?', "s", [$player]);
                        break;
    
                    default:
                        exitApi(400, "Unknown option");
                        break;
                }
            }
            else
            {
                $data = json_decode(file_get_contents("php://input"));
                if(!isset($data->leader))
                    exitApi(400, "Enter new leader");
                $queryResult = connectToDatabase('select `id` from `players` where `username` = ?', "s", [$data->leader]);
                if(empty($queryResult))
                    exitApi(404, "Player doesn't exists");
                connectToDatabase('update `guilds` set `leader` = ?', "i", [$queryResult[0]->id]);
            }
            http_response_code(204);
            break;

        case "DELETE":
            if(!isSingleGet())
                exitApi(400, "Enter guilds name");
            $queryResult = connectToDatabase('select `username` from `guilds` left join `players` on `guilds`.`leader` = `players`.`id` where `slug` = ?', "s" , [$requestUrlPart[$urlIndex + 1]]);
            if(empty($queryResult))
                exitApi(404, "Guild doesn't exists");
            $loginResult = isPlayerLogged($queryResult[0]->username, false);
            if($loginResult !== true)
            {
                if($loginResult->code == 401)
                    exitApi(401, "Only leader can delete guild");
                else
                    exitApi($loginResult->code, $loginResult->message);
            }
            connectToDatabase('delete from `guilds` where `slug` = ?', "s", [$requestUrlPart[$urlIndex + 1]]);
            http_response_code(204);
            break;

        case "OPTIONS":
            echo json_encode(["available-endpoints" => [
                'GET /guilds',
                'GET /guilds/{$slug}',
                'GET /guilds/{$slug}/members',
                'POST /guilds',
                'PATCH /guilds/{$slug}',
                'PATCH /guilds/{$slug}/add',
                'PATCH /guilds/{$slug}/kick',
                'DELETE /guilds/{$slug}'
            ]]);
            break;

        default:
            exitApi(501, "Method not implemented");
            return;
    }
?>