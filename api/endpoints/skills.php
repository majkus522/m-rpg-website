<?php
    require "playerLogged.php";

    switch($requestMethod)
    {
        case "GET":
        case "HEAD":
            if(!isSingleGet())
                exitApi(400, "Enter player");
            isPlayerLogged($requestUrlPart[$urlIndex + 1]);
            $query = 'select `skills`.* from `skills`, `players` where `skills`.`player` = `players`.`id` and `players`.`username` = ?';
            if(isset($requestUrlPart[$urlIndex + 2]))
            {
                if(!file_exists("data/skills/" . $requestUrlPart[$urlIndex + 2] . ".json"))
                    exitApi(404, "Skill doesn't exists");
                $query .= ' and `skills`.`skill` = ?';
                if(empty(connectToDatabase($query, array("ss", $requestUrlPart[$urlIndex + 1], $requestUrlPart[$urlIndex + 2]))))
                    exitApi(404, "Player doesn't have this skill");
                http_response_code(204);
            }
            else
            {
                $rarity = "(";
                $order = "";
                $types = "s";
                $parameters = array($requestUrlPart[$urlIndex + 1]);
                foreach($_GET as $key => $value)
                {
                    switch($key)
                    {
                        case "rarity":
                            $first = true;
                            foreach($value as $element)
                            {
                                if(!$first)
                                    $rarity .= " or";
                                $rarity .= ' rarity("E:/xampp/htdocs/m-rpg/api/data/skills", `skill`) = ?';
                                $first = false;
                                $types .= "s";
                                array_push($parameters, $element);
                            }
                            break;

                        case "toggle":
                            if(strtolower($value) != "true" && strtolower($value) != "false")
                                exitApi(400, "Incorect query string (toggle)");
                            $query .= " and `toggle` = " . strtolower($value);
                            break;
    
                        case "order":
                            if($order == "")
                            {
                                switch($value)
                                {
                                    case "rarity":
                                        $order = ' order by `rarity` asc';
                                        break;
    
                                    case "rarity-desc":
                                        $order = ' order by `rarity` desc';
                                        break;
                                }
                            }
                            break;
                    }
                }
                require "headerItems.php";
                if(strlen($rarity) > 2)
                    $query .= ' and ' . $rarity . ")";
                $query .= $order . ' limit ' . $limit . ' offset ' . $offset;
                $queryResult = connectToDatabase($query, array_merge(array($types), $parameters));
                if(empty($queryResult))
                    exitApi(404, "Can't find any skill matching conditions");
                header("Items-Count: " . sizeof($queryResult));
                if($requestMethod != "HEAD")
                    echo json_encode($queryResult);
                else
                    echo header("Content-Length: " . strlen(json_encode($queryResult)));
            }
            break;

        case "DELETE":
            if(!isset($requestUrlPart[$urlIndex + 1]))
                exitApi(400, "Enter player");
            isPlayerLogged($requestUrlPart[$urlIndex + 1]);
            if(!isset($requestUrlPart[$urlIndex + 2]))
                exitApi(400, "Enter skill");
            if(!file_exists("data/skills/" . $requestUrlPart[$urlIndex + 2] . ".json"))
                exitApi(404, "Skill doesn't exists");
            $query = 'delete from `skills` where `player` = (select `id` from `players` where `username` = ? limit 1) and `skill` = ?';
            connectToDatabase($query, array("is", $requestUrlPart[$urlIndex + 1], $requestUrlPart[$urlIndex + 2]));
            http_response_code(204);
            break;

        default:
            exitApi(501, "Method not implemented");
            return;
    }
?>