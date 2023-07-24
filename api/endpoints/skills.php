<?php
    require "playerLogged.php";

    switch($requestMethod)
    {
        case "GET":
        case "HEAD":
            if(!isSingleGet())
                exitApi(400, "Enter player");
            $login = isPlayerLogged($requestUrlPart[$urlIndex + 1]);
            if($login !== true)
                exitApi($login->code, $login->message);
            $query = 'select `skills`.* from `skills`, `players` where `skills`.`player` = `players`.`id` and `players`.`username` = "' . $requestUrlPart[$urlIndex + 1] . '"';
            if(isset($requestUrlPart[$urlIndex + 2]))
            {
                if(!file_exists("data/skills/" . $requestUrlPart[$urlIndex + 2] . ".json"))
                    exitApi(404, "Skill doesn't exists");
                $query .= ' and `skills`.`skill` = "' . $requestUrlPart[$urlIndex + 2] . '"';
                if(empty(connectToDatabase($query)))
                    exitApi(404, "Player doesn't have this skill");
            }
            else
            {
                $rarity = "(";
                $order = "";
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
                                $rarity .= ' `rarity` = "' . $element . '"';
                                $first = false;
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
                $queryResult = connectToDatabase($query);
                if(empty($queryResult))
                    exitApi(404, "Can't find any skill matching conditions");
                header("Items-Count: " . sizeof($queryResult));
                if($requestMethod != "HEAD")
                    echo json_encode($queryResult);
                else
                    echo header("Content-Length: " . strlen(json_encode($queryResult)));
            }
            break;

        case "POST":
            $data = json_decode(file_get_contents("php://input"));
            if(!isset($data->player))
                exitApi(400, "Enter player");
            $validPlayer = validUsername($data->player);
            if($validPlayer !== true || empty(connectToDatabase('select `id` from `players` where `username` = "' . $data->player . '"')))
                exitApi(404, "Player doesn't exists");
            $login = isPlayerLogged($data->player);
            if($login !== true)
                exitApi($login->code, $login->message);
            if(!isset($data->skill))
                exitApi(400, "Enter skill");
            if(!file_exists("data/skills/" . $data->skill . ".json"))
                exitApi(404, "Skill doesn't exists");
            $query = 'insert into `skills`(`skill`, `player`, `rarity`) values ("' . $data->skill . '", (select `id` from `players` where `username` = "' . $data->player . '" limit 1), "' . json_decode(file_get_contents("data/skills/" . $data->skill . ".json"))->rarity . '")';
            connectToDatabase($query);
            http_response_code(201);
            break;

        default:
            exitApi(501, "Method not implemented");
            return;
    }
?>