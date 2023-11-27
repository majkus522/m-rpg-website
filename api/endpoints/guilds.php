<?php
    require "playerLogged.php";

    switch($requestMethod)
    {
        case "GET":
        case "HEAD":
            if(isSingleGet())
            {
                $query = 'select * from `guilds` where `slug` = ?';
                $queryResult = connectToDatabase($query, "s", [$requestUrlPart[$urlIndex + 1]]);
                if(empty($queryResult))
                    exitApi(404, "Guild doesn't exists");
                $queryResult = $queryResult[0];
            }
            else
            {
                $query = 'select * from `guilds`';
                $queryResult = connectToDatabase($query);
                header("Return-Count: " . sizeof($queryResult));
            }
            if($requestMethod == "HEAD")
                header("Content-Length: " . strlen(json_encode($queryResult)));
            else
                echo json_encode($queryResult);
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

        case "DELETE":
            if(!isSingleGet())
                exitApi(400, "Enter guilds name");
            $queryResult = connectToDatabase('select `username` from `guilds` left join `players` on `guilds`.`leader` = `players`.`id` where `slug` = ?', "s" , [$requestUrlPart[$urlIndex + 1]]);
            if(empty($queryResult))
                exitApi(404, "Guild doesn't exists");
            isPlayerLogged($queryResult[0]->username);
            connectToDatabase('delete from `guilds` where `slug` = ?', "s", [$requestUrlPart[$urlIndex + 1]]);
            http_response_code(204);
            break;

        case "OPTIONS":
            echo json_encode(["available-endpoints" => [
                'GET /guilds',
                'GET /guilds/{$slug}',
                'POST /guilds',
                'DELETE /guilds/{$slug}'
            ]]);
            break;

        default:
            exitApi(501, "Method not implemented");
            return;
    }
?>