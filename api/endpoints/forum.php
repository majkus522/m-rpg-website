<?php
    switch($requestMethod)
    {
        case "GET":
        case "HEAD":
            require "headerItems.php";
            if(isSingleGet())
            {
                $query = 'with recursive cte(`id`, `player`, `text`, `master`, `title`, `slug`) as (select `id`, `player`, `text`, `master`, `title`, `slug` from `forum` where `slug` = ? union all select `f`.`id`, `f`.`player`, `f`.`text`, `f`.`master`, `f`.`title`, `f`.`slug` from `forum` `f` inner join `cte` on `f`.`master` = `cte`.`id`) select `id`, `player`, `text`, `master`, `title`, (select count(*) from `forum-likes` where `comment` = `id`) as likes from `cte` limit ? offset ?';
                $queryResult = connectToDatabase($query, "sii", [$requestUrlPart[$urlIndex + 1], $limit, $offset]);
                if(empty($queryResult))
                    exitApi(404, "Topic doesn't exists");
            }
            else
            {
                $query = 'select `title`, `slug`, `player` from `forum` where `master` is null limit ? offset ?';
                $queryResult = connectToDatabase($query, "ii", [$limit, $offset]);
                if(empty($queryResult))
                    exitApi(404, "Can't find any topic matching conditions");
            }
            $json = json_encode($queryResult);
            header("Return-Count: " . sizeof($queryResult));
            if($requestMethod == "HEAD")
                header("Content-Lenght: " . strlen($json));
            else
                echo $json;
            break;

        case "POST":
            $player = "";
            isPlayerLogged($player);
            $data = json_decode(file_get_contents("php://input"));
            if(!isset($data->text) || strlen($data->text) == 0)
                exitApi(400, "Enter text content");
            if(isset($data->master))
            {
                if(empty(connectToDatabase('select `id` from `forum` where `id` = ?', "i", [$data->master])))
                    exitApi(404, "Comment or topic doesn't exists");
                $query = 'insert into `forum`(`text`, `player`, `master`) values (?, ?, ?)';
                connectToDatabase($query, "sii", [$data->text, $player, $data->master]);
            }
            else if(isset($data->title))
            {
                if(strlen($data->title) == 0)
                    exitApi(400, "Enter title");
                $query = 'insert into `forum`(`title`, `player`, `slug`, `text`) values (?, ?, ?, ?)';
                connectToDatabase($query, "siss", [$data->title, $player, slugify($data->title), $data->text]);
            }
            else
                exitApi(400, "Enter topic or parent comment");
            http_response_code(201);
            break;

        case "PATCH":
            if(!isset($requestUrlPart[$urlIndex + 1]))
                exitApi(400, "Enter topic or comment");
            $player = "";
            isPlayerLogged($player);
            $data = json_decode(file_get_contents("php://input"));
            if(!isset($data->like))
                exitApi(400, "Enter new like value");
            if(gettype($data->like) != "boolean")
                exitApi(400, "Incorrect like value");
            if($data->like)
            {
                if(empty(connectToDatabase('select * from `forum-likes` where `player` = ? and `comment` = ?', "ii", [$player, $requestUrlPart[$urlIndex + 1]])))
                    connectToDatabase('insert into `forum-likes`(`player`, `comment`) values (?, ?)', "ii", [$player, $requestUrlPart[$urlIndex + 1]]);
            }
            else
                connectToDatabase('delete from `forum-likes` where `player` = ? and `comment` = ?', "ii", [$player, $requestUrlPart[$urlIndex + 1]]);
            http_response_code(204);
            break;

        case "DELETE":
            if(!isset($requestUrlPart[$urlIndex + 1]))
                exitApi(400, "Enter topic or comment");
            $type = strtolower(isset($_GET["type"]) ? $_GET["type"] : "topic");
            switch($type)
            {
                case "topic":
                    $finder = "slug";
                    $types = "s";
                    break;

                case "comment":
                    $finder = "id";
                    $types = "i";
                    break;

                default:
                    exitApi(400, "Incorrect delete type");
                    break;
            }
            $queryResult = connectToDatabase('select `username` from `forum` left join `players` on `players`.`id` = `forum`.`player` where `' . $finder . '` = ?', $types, [$requestUrlPart[$urlIndex + 1]]);
            if(empty($queryResult))
                exitApi(404, "The $type is already deleted or never existed");
            $loginResult = isPlayerLogged($queryResult[0]->username, false);
            if($loginResult !== true)
            {
                if($loginResult->code == 401)
                    exitApi(401, "You can't delete someones else " . $type);
                else
                    exitApi($loginResult->code, $loginResult->message);
            }
            connectToDatabase('delete from `forum` where `' . $finder . '` = ?', $types, [$requestUrlPart[$urlIndex + 1]]);
            http_response_code(204);
            break;

        case "OPTIONS":
            echo json_encode(["available-endpoints" => [
                'GET /forum',
                'GET /forum/${slug}',
                'POST /forum',
                'PATCH /forum/${comment}',
                'DELETE /forum/${slug}'
            ]]);
            break;

        default:
            exitApi(501, "Method not implemented");
            return;
    }
?>