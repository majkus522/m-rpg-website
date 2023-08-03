<?php
    switch($requestMethod)
    {
        case "GET":
            $player = 1;
            $query = 'select `circles`.`name`, `circles`.`slug`, (select count(`circles-likes`.`player`) from `circles-likes` where `circles-likes`.`circle` = `circles`.`id`) as "likes", ifnull((select (`circles-likes`.`player` = ' . $player . ') from `circles-likes` where `circles-likes`.`circle` = `circles`.`id` group by `circles-likes`.`circle`), 0) as "liked" from `circles` left join `players` on `players`.`id` = `circles`.`player` where !`circles`.`private`';
            require "headerItems.php";
            $query .= ' limit ' . $limit . ' offset ' . $offset;
            $queryResult = connectToDatabase($query);
            echo json_encode($queryResult);
            break;

        default:
            exitApi(501, "Method not implemented");
            break;
    } 
?>