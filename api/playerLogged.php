<?php
    function isPlayerLogged(string $player, bool $exit = true):object|null
    {
        $headerKey = getHeader("Session-Key");
        if($headerKey === false)
            return exitLogin(400, "Enter player session key", $exit);
        $headerType = getHeader("Session-Type");
        if($headerType === false)
            return exitLogin(400, "Enter player session type", $exit);
        $apiResult = callApi("endpoints/players/{$player}/session", "GET", ["Session-Key: {$headerKey}", "Session-Type: {$headerType}"]);
        if($apiResult->code >= 200 && $apiResult->code < 300)
            return null;
        return exitLogin($apiResult->code, $apiResult->content->message, $exit);
    }

    function exitLogin(int $code, string $message, bool $exit):object|null
    {
        if($exit)
            exitApi($code, $message);
        else
            return new LoginResult($code, $message);
    }
?>