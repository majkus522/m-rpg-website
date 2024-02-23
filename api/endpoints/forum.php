<?php
    switch($requestMethod)
    {
        case "GET":
        case "HEAD":
            require "headerItems.php";
            if(isSingleGet())
            {
                $query = 'with recursive cte(`id`, `player`, `text`, `master`, `title`, `slug`, `time`) as (select `id`, `player`, `text`, `master`, `title`, `slug`, `time` from `forum` where `slug` = ? union all select `f`.`id`, `f`.`player`, `f`.`text`, `f`.`master`, `f`.`title`, `f`.`slug`, `f`.`time` from `forum` `f` inner join `cte` on `f`.`master` = `cte`.`id`) select `id`, (select `username` from `players` where `id` = `player`) as "player", `text`, `master`, `title`, (select count(*) from `forum-likes` where `comment` = `id`) as "likes", `time`';
                $types = "s";
                $parameters = [$requestUrlPart[$urlIndex + 1]];
                $player = "";
                if(isPlayerLogged($player, false) === true)
                {
                    $query .= ', (select count(*) > 0 from `forum-likes` where `comment` = `cte`.`id` and `forum-likes`.`player` = (select `id` from `players` where `username` = ? limit 1)) as "liked"';
                    $types .= "s";
                    array_push($parameters, $player);
                }
                $query .= ' from `cte` order by `time` asc limit ? offset ?';
                $queryResult = connectToDatabase($query, $types . "ii", array_merge($parameters, [$limit, $offset]));
                if(empty($queryResult))
                    exitApi(404, "Post doesn't exists");
            }
            else
            {
                $query = 'with recursive cte as ( select `id`, `slug`, (select count(*) from `forum-likes` where `comment` = `forum`.`id`) as "likes" from `forum` union all select `f`.`id`, `cte`.`slug`, (select count(*) from `forum-likes` where `comment` = `f`.`id`) as "likes" from `forum` `f` inner join `cte` on `f`.`master` = `cte`.`id` ) select `title`, `slug`, (select `username` from `players` where `id` = `player`) as "player", `time`, (select cast(sum(`likes`) as INT) from `cte` where `slug` is not null and `cte`.`slug` = `forum`.`slug` group by `slug`) as "likes", (select count(*) - 1 from `cte` where `slug` is not null and `cte`.`slug` = `forum`.`slug` group by `slug`) as "comments" from `forum` where `slug` is not null ';
                $parameters = [];
                $types = "";
                $order = "";
                foreach($_GET as $key => $value)
                {
                    switch($key)
                    {
                        case "author":
                            if(empty(connectToDatabase('select `id` from `players` where `username` = ?', "s", [$value])))
                                exitApi(404, "Player doesn't exists");
                            array_push($parameters, $value);
                            $query .= ' and `player` = ?';
                            $types .= "s";
                            break;

                        case "order":
                            switch($value)
                            {
                                case "likes":
                                    $order = ' order by `likes` asc';
                                    break;

                                case "likes-desc":
                                    $order = ' order by `likes` desc';
                                    break;

                                case "comments":
                                    $order = ' order by `comments` asc';
                                    break;
    
                                case "comments-desc":
                                    $order = ' order by `comments` desc';
                                    break;

                                case "time":
                                    $order = ' order by `time` asc';
                                    break;
        
                                case "time-desc":
                                    $order = ' order by `time` desc';
                                    break;

                                default:
                                    exitApi(400, "Unknown order parameter $value");
                                    break;
                            }
                            break;

                        default:
                            exitApi(400, "Unknown query string parameter $key");
                            break;
                    }
                }
                $query .= $order . ' limit ? offset ?';
                $types .= "ii";
                $queryResult = connectToDatabase($query, $types, array_merge($parameters, [$limit, $offset]));
                if(empty($queryResult))
                    exitApi(404, "Can't find any post matching conditions");
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
            $insertType = "id";
            if(isset($data->master))
            {
                if(empty(connectToDatabase('select `id` from `forum` where `id` = ?', "i", [$data->master])))
                    exitApi(404, "Comment or post doesn't exists");
                $query = 'insert into `forum`(`text`, `player`, `master`) values (?, ?, ?)';
                connectToDatabase($query, "sii", [$data->text, $player, $data->master], $insertId);
            }
            else if(isset($data->title))
            {
                if(strlen($data->title) < 3)
                    exitApi(400, "Title is too short");
                $query = 'insert into `forum`(`title`, `player`, `slug`, `text`) values (?, ?, ?, ?)';
                connectToDatabase($query, "siss", [$data->title, $player, slugify($data->title, "forum", "slug"), $data->text], $insertId);
                $insertId = connectToDatabase('select `slug` from `forum` where `id` = ?', "i", [$insertId])[0]->slug;
                $insertType = "slug";
            }
            else
                exitApi(400, "Enter post title or parent comment");
            http_response_code(201);
            echo json_encode([$insertType => $insertId]);
            break;

        case "PATCH":
            if(!isset($requestUrlPart[$urlIndex + 1]))
                exitApi(400, "Enter post or comment");
            if(empty(connectToDatabase('select `id` from `forum` where `id` = ?', "i", [$requestUrlPart[$urlIndex + 1]])))
                exitApi(404, "Comment or post doesn't exists");
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
                exitApi(400, "Enter post or comment");
            $queryResult = connectToDatabase('select `username` from `forum` left join `players` on `players`.`id` = `forum`.`player` where `forum`.`id` = ?', "i", [$requestUrlPart[$urlIndex + 1]]);
            if(empty($queryResult))
                exitApi(404, "The post or comment is already deleted or never existed");
            $loginResult = isPlayerLogged($queryResult[0]->username, false);
            if($loginResult !== true)
            {
                if($loginResult->code == 401)
                    exitApi(401, "You can't delete someones else post or comment");
                else
                    exitApi($loginResult->code, $loginResult->message);
            }
            connectToDatabase('delete from `forum` where `id` = ?', "i", [$requestUrlPart[$urlIndex + 1]]);
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