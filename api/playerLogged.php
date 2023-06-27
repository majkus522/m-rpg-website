<?php
    $header = getHeader("Password");
    if($header === false)
        exitApi(400, "Enter player password");
    $apiResult = callApi("endpoints/players/" . $requestUrlPart[$urlIndex + 1] . "/logged", "GET", ["Password: " . $header]);
    if($apiResult[1] != 200)
        exitApi($apiResult[1], $apiResult[0]->message);
?>