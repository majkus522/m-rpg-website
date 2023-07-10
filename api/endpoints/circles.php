<?php
    require "playerLogged.php";

    switch($requestMethod)
    {
        case "GET":
            if(isSingleGet())
            {
                $query = 'select * from `circles` where (!`private`';
                $header = getHeader("Player");
                if($header !== false)
                {
                    $login = isPlayerLogged($header);
                    if($login === true)
                    {
                        $query .= ' or `player` = (select `player` from `players-sessions` where `key` = "' . getHeader("Session-Key") . '" limit 1)';
                    }
                }
                $query .= ') and `slug` = "' . $requestUrlPart[$urlIndex + 1] . '" limit 1';
                $queryResult = connectToDatabase($query);
                if(empty($queryResult))
                    exitApi(404, "Circle doesn't exists");
                $result = $queryResult[0];
            }
            else
            {
                $query = 'select * from `circles` where (!`private`';
                $header = getHeader("Player");
                if($header !== false)
                {
                    $login = isPlayerLogged($header);
                    if($login === true)
                    {
                        $query .= ' or `player` = (select `player` from `players-sessions` where `key` = "' . getHeader("Session-Key") . '" limit 1)';
                    }
                }
                $query .= ')';
                $order = "";
                foreach($_GET as $key => $value)
                {
                    switch($key)
                    {
                        case "minMana":
                            if(!is_numeric($value) || $value < 0)
                                exitApi(400, "Incorect query string (minMana)");
                            $query .= ' and `mana` >= ' . $value;
                            break;

                        case "maxMana":
                            if(!is_numeric($value) || $value < 0)
                                exitApi(400, "Incorect query string (maxMana)");
                            $query .= ' and `mana` <= ' . $value;
                            break;

                        case "order":
                            if($order == "")
                            {
                                switch($value)
                                {
                                    case "mana-desc":
                                        $order = ' order by `mana` desc';
                                        break;
    
                                    case "mana":
                                        $order = ' order by `mana` asc';
                                        break;
                                }
                            }
                            break;
                    }
                }
                require "headerItems.php";
                $query .= $order . ' limit ' . $limit . ' offset ' . $offset;
                $queryResult = connectToDatabase($query);
                if(empty($queryResult))
                    exitApi(404, "Can't find any circle matching conditions");
                header("Items-Count: " . sizeof($queryResult));
                $result = $queryResult;
            }
            echo json_encode($result);
            break;

        default:
            exitApi(501, "Method not implemented");
            return;
    }
?>