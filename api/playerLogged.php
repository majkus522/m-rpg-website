<?php
    $headerKey = getHeader("Session-Key");
    if($headerKey === false)
        exitApi(400, "Enter player session key");
    $headerType = getHeader("Session-Type");
    if($headerType === false)
        exitApi(400, "Enter player session type");
    $apiResult = callApi("endpoints/players/" . $requestUrlPart[$urlIndex + 1] . "/session", "GET", ["Session-Key: " . $headerKey, "Session-Type: " . $headerType]);
    if($apiResult[1] != 200)
        exitApi($apiResult[1], $apiResult[0]->message);
?>