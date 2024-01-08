<?php
    $allowedParams = ["level" => "i", "money" => "i"];
    foreach(json_decode(file_get_contents("data/playerStats.json")) as $element)
        $allowedParams[$element->short] = "i";
    $allowedParams["clazz"] = "s";

    switch($requestMethod)
    {
        case "GET":
        case "HEAD":
            if(!isSingleGet())
                exitApi(400, "Enter player");
            isPlayerLogged($requestUrlPart[$urlIndex + 1]);
            $queryResult = connectToDatabase('select * from `fake-status` where `player` = (select `id` from `players` where `username` = ? limit 1) limit 1', "s", [$requestUrlPart[$urlIndex + 1]]);
            if(empty($queryResult))
                exitApi(404, "Player doesn't have fake status");
            if($requestMethod == "HEAD")
                header("Content-Length: " . strlen(json_encode($queryResult[0])));
            else
                echo json_encode($queryResult[0]);
            break;

        case "POST":
            if(!isSingleGet())
                exitApi(400, "Enter player");
            isPlayerLogged($requestUrlPart[$urlIndex + 1]);
            $queryResult = connectToDatabase('select `id` from `players` where `username` = ? limit 1', "s", [$requestUrlPart[$urlIndex + 1]]);
            if(!empty(connectToDatabase('select `id` from `fake-status` where `player` = ? limit 1', "i", [$queryResult[0]->id])))
                exitApi(404, "Player already have a fake status");
            $data = json_decode(file_get_contents("php://input"));
            $query = 'insert into `fake-status` (`player`';
            $queryParameters = ') values (?';
            $types = "i";
            $parameters = [$queryResult[0]->id];
            foreach($allowedParams as $key => $value)
            {
                if(!isset($data->$key))
                    exitApi(400, "Enter " . $key);
                if($key == "clazz")
                {
                    if(!(file_exists("data/classes/" . $data->$key . ".json") || $data->$key === "none"))
                        exitApi(400, "Incorrect value: " . $key);
                }
                else
                {
                    if(!(is_numeric($data->$key) && $data->$key >= 0))
                        exitApi(400, "Incorrect value: " . $key);
                }
                $query .= ", `$key`";
                $queryParameters .= ', ?';
                $types .= $value;
                array_push($parameters, $data->$key === "none" ? null : $data->$key);
            }
            connectToDatabase($query . $queryParameters . ')', $types, $parameters);
            http_response_code(201);
            break;

        case "PATCH":
            if(!isSingleGet())
                exitApi(400, "Enter player");
            isPlayerLogged($requestUrlPart[$urlIndex + 1]);
            if(empty(connectToDatabase('select `id` from `fake-status` where `player` = (select `id` from `players` where `username` = ?)', "s", [$requestUrlPart[$urlIndex + 1]])))
                exitApi(404, "Player doesn't have fake status");
            $data = json_decode(file_get_contents("php://input"));
            $types = "";
            $parameters = [];
            $query = 'update `fake-status` set ';
            $first = true;
            foreach($allowedParams as $key => $value)
            {
                if(isset($data->$key))
                {
                    if($key == "clazz")
                    {
                        if(!(file_exists("data/classes/" . $data->$key . ".json") || $data->$key === "none"))
                            exitApi(400, "Incorrect value: " . $key);
                    }
                    else
                    {
                        if(!(is_numeric($data->$key) && $data->$key >= 0))
                            exitApi(400, "Incorrect value: " . $key);
                    }
                    if(!$first)
                        $query .= ', ';
                    $query .= "`$key` = ? ";
                    array_push($parameters, $data->$key === "none" ? null : $data->$key);
                    $types .= $value;
                    $first = false;
                }
            }
            if(empty($parameters))
                exitApi(400, "Enter some changes");
            connectToDatabase($query . ' where `player` = (select `id` from `players` where `username` = ?)', $types . "s", array_merge($parameters, [$requestUrlPart[$urlIndex + 1]]));
            http_response_code(204);
            break;

        case "OPTIONS":
            echo json_encode(["available-endpoints" => [
                'GET /fake-status/${username}',
                'POST /fake-status/${username}',
                'PATCH /fake-status/${username}'
            ]]);
            break;

        default:
            exitApi(501, "Method not implemented");
            return;
    }
?>