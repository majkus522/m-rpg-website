<?php
    require "databaseController.php";
    connectToDatabase('delete from `players-sessions` where `temp` and hour(timediff(now(), `date`)) > 24');
    connectToDatabase('delete from `password-recovery` where hour(timediff(now(), `date`)) > 24');
    connectToDatabase('delete from `guilds` where (select count(`players`.`id`) from `players` where `guild` = `guilds`.`id`) = 0');
    connectToDatabase('delete from `players` where `id` in (select `player` from `players-deletion` where `date` < now())')
?>