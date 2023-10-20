<?php
    require "databaseController.php";
    connectToDatabase('delete from `players-sessions` where `temp` and hour(timediff(now(), `date`)) > 24');
    connectToDatabase('delete from `password-recovery` where hour(timediff(now(), `date`)) > 24');
?>