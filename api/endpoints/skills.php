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
                $rarityTypes = "";
                $parameters = array($requestUrlPart[$urlIndex + 1]);
                $rarityParameters = array();
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
                                $rarity .= ' skill_rarity("E:/xampp/htdocs/m-rpg/api/data/skills", `skill`) = ?';
                                $first = false;
                                $rarityTypes .= "s";
                                array_push($rarityParameters, $element);
                            }
                            break;

                        case "toggle":
                            if(strtolower($value) != "true" && strtolower($value) != "false")
                                exitApi(400, "Incorrect query string (toggle)");
                            $query .= " and `toggle` = " . strtolower($value);
                            break;

                        case "search":
                            foreach(explode(" ", $value) as $element)
                            {
                                $query .= ' and (LOWER(`skill`) like ? or LOWER(skill_label("E:/xampp/htdocs/m-rpg/api/data/skills", `skill`)) like ?)';
                                array_push($parameters, "%" . strtolower($element) . "%", "%" . strtolower($element) . "%");
                                $types .= "ss";
                            }
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
                $queryResult = connectToDatabase($query, array_merge(array($types . $rarityTypes), $parameters, $rarityParameters));
                if(empty($queryResult))
                    exitApi(404, "Can't find any skill matching conditions");
                header("Result-Count: " . sizeof($queryResult));
                if($requestMethod != "HEAD")
                    echo json_encode($queryResult);
                else
                    echo header("Content-Length: " . strlen(json_encode($queryResult)));
            }
            break;

        case "PATCH":
            if(!isset($requestUrlPart[$urlIndex + 1]))
                exitApi(400, "Enter player");
            isPlayerLogged($requestUrlPart[$urlIndex + 1]);
            if(!isset($requestUrlPart[$urlIndex + 2]))
                exitApi(400, "Enter skill");
            if(!file_exists("data/skills/" . $requestUrlPart[$urlIndex + 2] . ".json"))
                exitApi(404, "Skill doesn't exists");
            if(!json_decode(file_get_contents("data/skills/" . $requestUrlPart[$urlIndex + 2] . ".json"))->toggle)
                exitApi(400, "Skill can't be toggled");
            $data = file_get_contents("php://input");
            if(strlen($data) < 1)
                exitApi(400, "Enter new toggle value");
            $toggle = filter_var($data, FILTER_VALIDATE_BOOLEAN);
            $query = 'update `skills` set `toggle` = ? where `player` = (select `id` from `players` where `username` = ? limit 1) and `skill` = ?';
            connectToDatabase($query, array("iss", (int) $toggle, $requestUrlPart[$urlIndex + 1], $requestUrlPart[$urlIndex + 2]));
            http_response_code(204);
            break;

        default:
            exitApi(501, "Method not implemented");
            return;
    }
?>