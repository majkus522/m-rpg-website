<?php
    require "playerLogged.php";
    $allowedParams = array("level", "money", "str", "agl", "chr", "intl", "def", "vtl");

    switch($requestMethod)
    {
        case "GET":
        case "HEAD":
            if(!isSingleGet())
                exitApi(400, "Enter player");
            $queryResult = connectToDatabase('select `id` from `players` where `username` = ? limit 1', array("s", $requestUrlPart[$urlIndex + 1]));
            if(empty($queryResult))
                exitApi(404, "Player doesn't exists");
            isPlayerLogged($requestUrlPart[$urlIndex + 1]);
            $queryResult = connectToDatabase('select * from `fake-status` where `player` = ? limit 1', array("i", $queryResult[0]->id));
            if(empty($queryResult))
                exitApi(404, "Player doesn't have fake status");
            if($requestMethod == "GET")
                echo json_encode($queryResult[0]);
            echo header("Content-Length: " . strlen(json_encode($queryResult[0])));
            break;

        case "POST":
            if(!isSingleGet())
                exitApi(400, "Enter player");
            $queryResult = connectToDatabase('select `id` from `players` where `username` = ? limit 1', array("s", $requestUrlPart[$urlIndex + 1]));
            if(empty($queryResult))
                exitApi(404, "Player doesn't exists");
            isPlayerLogged($requestUrlPart[$urlIndex + 1]);
            if(!empty(connectToDatabase('select `id` from `fake-status` where `player` = ?', array("i", $queryResult[0]->id))))
                exitApi(400, "Player already have a fake status");
            $data = json_decode(file_get_contents("php://input"));
            $query = 'insert into `fake-status` (`player`';
            $queryParameters = ') values (?';
            $types = "i";
            $parameters = array($queryResult[0]->id);
            foreach($allowedParams as $element)
            {
                if(!(is_numeric($data->$element) && $data->$element >= 0))
                    exitApi(400, "Incorect value " . $element);
                if(!isset($data->$element))
                    exitApi(400, "Enter " . $element);
                $query .= ", `$element`";
                $queryParameters .= ', ?';
                $types .= "i";
                array_push($parameters, $data->$element);
            }
            connectToDatabase($query . $queryParameters . ')', array_merge(array($types), $parameters));
            http_response_code(201);
            break;

        case "PATCH":
            if(!isSingleGet())
                exitApi(400, "Enter player");
            $queryResult = connectToDatabase('select `id` from `players` where `username` = ? limit 1', array("s", $requestUrlPart[$urlIndex + 1]));
            if(empty($queryResult))
                exitApi(404, "Player doesn't exists");
            isPlayerLogged($requestUrlPart[$urlIndex + 1]);
            if(empty(connectToDatabase('select `id` from `fake-status` where `player` = ?', array("i", $queryResult[0]->id))))
                exitApi(400, "Player doesn't have fake status");
            $data = json_decode(file_get_contents("php://input"));
            $types = "";
            $parameters = array();
            $query = 'update `fake-status` set ';
            $first = true;
            foreach($allowedParams as $element)
            {
                if(isset($data->$element))
                {
                    if(!(is_numeric($data->$element) && $data->$element >= 0))
                        exitApi(400, "Incorect value " . $element);
                    if(!$first)
                        $query .= ', ';
                    $query .= "`$element` = ? ";
                    array_push($parameters, $data->$element);
                    $types .= "i";
                    $first = false;
                }
            }
            if(empty($parameters))
                exitApi(400, "Enter some changes");
            connectToDatabase($query . ' where `player` = ?', array_merge(array($types . "i"), $parameters, array($queryResult[0]->id)));
            break;

        default:
            exitApi(501, "Method not implemented");
            return;
    }
?>