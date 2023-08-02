<?php
    require "../imports/loginCheck.php";
    if(!$validLogin)
        exitApi(401, "Login first");
    $query = 'select * from `skills` where `player` = (select `id` from `players` where `username` = ?)';
    $order = "";
    foreach($_GET as $key => $value)
    {
        switch($key)
        {
            case "rarity":
                $query .= ' and (';
                $first = true;
                foreach($value as $element)
                {
                    if(!$first)
                        $query .= ' or ';
                    $query .= '`rarity` = "' . $element . '"';
                    $first = false;
                }
                $query .= ')';
                break;
            
            case "order":
                switch($value)
                {
                    case "rarity":
                        $order = ' order by `rarity` asc';
                        break;

                    case "rarity-desc":
                        $order = ' order by `rarity` desc';
                        break;
                }
                break;
        }
    }
    $queryResult = connectToDatabase($query . $order, array("s", $_COOKIE["username"]));
    echo json_encode($queryResult);
?>