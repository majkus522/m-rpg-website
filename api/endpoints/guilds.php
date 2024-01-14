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
                            $player = "";
                            isPlayerLogged($player);
                            if(empty(connectToDatabase('select `players`.`id` from `players` left join `guilds` on `guilds`.`id` = `players`.`guild` where `slug` = ? and `username` = ?', "ss", [$requestUrlPart[$urlIndex + 1], $player])))
                                exitApi(400, "You are not part of this guild");
                            $queryResult = connectToDatabase('select `username` from `guilds` join `players` on `players`.`id` = `guilds`.`leader` where `slug` = ? union select `username` from `guilds` join `players` on `players`.`id` = `guilds`.`vice_leader` where `slug` = ?', "ss", [$requestUrlPart[$urlIndex + 1], $requestUrlPart[$urlIndex + 1]]);
                            $result = [["username" => $queryResult[0]->username, "type" => "leader"]];
                            if(sizeof($queryResult) > 1)
                                array_push($result, ["username" => $queryResult[1]->username, "type" => "vice_leader"]);
                            $queryResult = connectToDatabase('select `username` from `guilds` join `players` on `players`.`guild` = `guilds`.`id` where `slug` = ? and `players`.`id` != `guilds`.`leader` and (`players`.`id` != `guilds`.`vice_leader` or `guilds`.`vice_leader` is null)', "s", [$requestUrlPart[$urlIndex + 1]]);
                            foreach($queryResult as $element)
                                array_push($result, ["username" => $element->username, "type" => "member"]);
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
            $guild = $requestUrlPart[$urlIndex + 1];
            $queryResult = connectToDatabase('select * from `guilds` where `slug` = ?', "s", [$guild]);
            if(empty($queryResult))
                exitApi(404, "Guild doesn't exists");
            $player = "";
            isPlayerLogged($player);
            if(isset($requestUrlPart[$urlIndex + 2]))
            {
                $member = file_get_contents("php://input");
                if(strlen($member) < 3)
                    exitApi(400, "Enter player");
                if(empty(connectToDatabase('select `id` from `players` where `username` = ?', "s", [$member])))
                    exitApi(404, "Player doesn't exists");
                if(checkPermission($player, $guild))
                    if(!($requestUrlPart[$urlIndex + 2] == "kick" && $player == $member))
                        exitApi(401, "You don't have permission to do this (vice leader)");
                switch($requestUrlPart[$urlIndex + 2])
                {
                    case "add":
                        if(empty(connectToDatabase('select `guild` from `players` where `username` = ? and `guild` is null', "s", [$member])))
                            exitApi(400, "Player is already part of the guild");
                        connectToDatabase('update `players` set `guild` = ? where `username` = ?', "is", [$queryResult[0]->id, $member]);
                        break;
    
                    case "kick":
                        if(empty(connectToDatabase('select `id` from `players` where `username` = ? and `guild` = ?', "si", [$member, $queryResult[0]->id])))
                            exitApi(400, "Player isn't part of the guild");
                        if(!checkPermission($member, $guild, "leader"))
                            exitApi(401, "You can't kick guilds leader");
                        if(!checkPermission($member, $guild, "vice_leader"))
                            connectToDatabase('update `guilds` set `vice_leader` = null where `slug` = ?', "s", [$guild]);
                        connectToDatabase('update `players` set `guild` = null where `username` = ?', "s", [$member]);
                        break;
    
                    default:
                        exitApi(400, "Unknown option");
                        break;
                }
            }
            else
            {
                $data = json_decode(file_get_contents("php://input"));
                if(empty((array)$data))
                    exitApi(400, "Enter some changes");
                if(isset($data->leader))
                {
                    if(checkPermission($player, $guild, "leader"))
                        exitApi(401, "You don't have permission to do this (leader)");
                    $queryResult = connectToDatabase('select `id` from `players` where `username` = ?', "s", [$data->leader]);
                    if(empty($queryResult))
                        exitApi(404, "Player doesn't exists (leader)");
                    if($queryResult[0]->id == connectToDatabase('select `vice_leader` from `guilds` where `slug` = ?', "s", [$guild])[0]->vice_leader)
                        swapPositions($guild);
                    else
                        connectToDatabase('update `guilds` set `leader` = ? where `slug` = ?', "is", [$queryResult[0]->id, $guild]);
                }
                if(isset($data->vice_leader))
                {
                    if(checkPermission($player, $guild))
                        exitApi(401, "You don't have permission to do this (vice leader)");
                    $queryResult = connectToDatabase('select `id` from `players` where `username` = ?', "s", [$data->vice_leader]);
                    if(empty($queryResult))
                        exitApi(404, "Player doesn't exists (vice leader)");
                    if(!checkPermission($queryResult[0]->id, $guild, "leader"))
                        exitApi(401, "You can't downgrade your leader");
                    connectToDatabase('update `guilds` set `vice_leader` = ? where `slug` = ?', "is", [$queryResult[0]->id, $guild]);
                }
            }
            http_response_code(204);
            break;

        case "DELETE":
            if(!isSingleGet())
                exitApi(400, "Enter guilds name");
            $queryResult = connectToDatabase('select * from `guilds` where `slug` = ?', "s" , [$requestUrlPart[$urlIndex + 1]]);
            if(empty($queryResult))
                exitApi(404, "Guild is already deleted");
            $player = "";
            isPlayerLogged($player);
            if(checkPermission($player, $requestUrlPart[$urlIndex + 1], "leader"))
                exitApi(401, "Only leader can delete a guild");
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

    function checkPermission(string $player, string $guild, string $permission = "vice_leader"):bool
    {
        switch($permission)
        {
            case "leader":
                return empty(connectToDatabase('select `guilds`.`id` from `guilds`, `players` where `guilds`.`leader` = `players`.`id` and `players`.`username` = ? and `guilds`.`slug` = ?', "ss", [$player, $guild]));

            case "vice_leader":
                return empty(connectToDatabase('select `guilds`.`id` from `guilds`, `players` where (`guilds`.`leader` = `players`.`id` or `guilds`.`vice_leader` = `players`.`id`) and `players`.`username` = ? and `guilds`.`slug` = ?', "ss", [$player, $guild]));
        }
        return false;
    }

    function swapPositions(string $guild)
    {
        $queryResult = connectToDatabase('select * from `guilds` where `slug` = ?', "s", [$guild]);
        connectToDatabase('update `guilds` set `leader` = ? where `slug` = ?', "is", [$queryResult[0]->vice_leader, $guild]);
        connectToDatabase('update `guilds` set `vice_leader` = ? where `slug` = ?', "is", [$queryResult[0]->leader, $guild]);
    }
?>