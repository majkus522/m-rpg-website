<?php
    function isPlayerLogged(string $player):object|true
    {
        $headerKey = getHeader("Session-Key");
        if($headerKey === false)
            return new LoginResult(400, "Enter player session key");
        $headerType = getHeader("Session-Type");
        if($headerType === false)
            return new LoginResult(400, "Enter player session type");
        $apiResult = callApi("endpoints/players/" . $player . "/session", "GET", ["Session-Key: " . $headerKey, "Session-Type: " . $headerType]);
        if($apiResult[1] != 200)
            return new LoginResult($apiResult[1], $apiResult[0]->message);
        return true;
    }
?>