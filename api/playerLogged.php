<?php
    function isPlayerLogged(&$player = "", bool $exit = true):object|bool
    {
        $headerKey = getHeader("Session-Key");
        if($headerKey === false)
            return exitLogin(400, "Enter player session key", $exit);
        $headerId = getHeader("Session-ID");
        if($headerId === false)
            return exitLogin(400, "Enter player session id", $exit);
        $idPlayer = connectToDatabase('select `username` from `players` where `id` = ?', "i", [$headerId])[0]->username;
        if(strlen($player) == 0)
            $player = $idPlayer;
        $apiResult = callApi("players/{$player}/session", "GET", ["Session-Key: $headerKey", "Session-ID: $headerId"]);
        if($apiResult->code >= 200 && $apiResult->code < 300)
            return true;
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