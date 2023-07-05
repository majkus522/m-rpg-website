<?php
    switch($requestMethod)
    {
        case "GET":
            if(!isSingleGet())
                exitApi(400, "Enter player");
            require "playerLogged.php";
            $query = 'select `skills`.* from `skills`, `players` where `skills`.`player` = `players`.`id` and `players`.`username` = "' . $requestUrlPart[$urlIndex + 1] . '"';
            $rarityPresent = false;
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
                }
            }
            require "headerItems.php";
            $query .= ' limit ' . $limit . ' offset ' . $offset;
            $queryResult = connectToDatabase($query);
            if(empty($queryResult))
                exitApi(404, "Can't find any skill matching conditions");
            echo json_encode($queryResult);
            break;

        default:
            exitApi(501, "Method not implemented");
            return;
    }
?>