<?php
    require "playerLogged.php";

    switch($requestMethod)
    {
        case "GET":
            if(!isSingleGet())
                exitApi(400, "Enter player");
            $login = isPlayerLogged($requestUrlPart[$urlIndex + 1]);
            if($login !== true)
                exitApi($login->code, $login->message);
            $query = 'select `skills`.* from `skills`, `players` where `skills`.`player` = `players`.`id` and `players`.`username` = "' . $requestUrlPart[$urlIndex + 1] . '"';
            $rarityPresent = false;
            $order = "";
            foreach($_GET as $key => $value)
            {
                switch($key)
                {
                    case "rarity":
                        if(!$rarityPresent)
                        {
                            $query .= ' and `rarity` = "' . $value . '"';
                            $rarityPresent = true;
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
            $query .= $order . ' limit ' . $limit . ' offset ' . $offset;
            $queryResult = connectToDatabase($query);
            if(empty($queryResult))
                exitApi(404, "Can't find any skill matching conditions");
            header("Items-Count: " . sizeof($queryResult));
            echo json_encode($queryResult);
            break;

        default:
            exitApi(501, "Method not implemented");
            return;
    }
?>