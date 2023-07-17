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
            break;

        default:
            exitApi(501, "Method not implemented");
            return;
    }
?>