<?php
    function consoleLog(string $input):void
    {
        file_put_contents("log.txt", json_encode($input) . "\r\n", FILE_APPEND);
    }
?>