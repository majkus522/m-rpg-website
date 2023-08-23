<?php
    require "errorHandler.php";
    require "../imports/common.php";
    require "databaseController.php";
    require "logger.php";
    require "hasher.php";

    header("Content-Type: application/json");
    $requestUrlPart = explode("/", clearRequestUrl());
    $requestMethod = strtoupper($_SERVER["REQUEST_METHOD"]);
    $methodHeader = getHeader("X-HTTP-Method-Override");
    if($methodHeader != false)
        $requestMethod = strtoupper($methodHeader);

    for($index = 0; $index < sizeof($requestUrlPart); $index++)
    {
        if(strtolower($requestUrlPart[$index]) == "api")
        {
            $urlIndex = $index + 2;
            break;
        }
    }

    if($requestUrlPart[$urlIndex - 1] != "controllers" && $requestUrlPart[$urlIndex - 1] != "endpoints")
        exitApi(404, "Unknown option for url " . clearRequestUrl());

    if(in_array($requestUrlPart[$urlIndex] . ".php", scandir($requestUrlPart[$urlIndex - 1])))
        require $requestUrlPart[$urlIndex - 1] . "/" . $requestUrlPart[$urlIndex] . ".php";
    else
        exitApi(404, "Unknown endpoint");

    function exitApi(int $code, string|object $message):void
    {
        global $requestMethod;
        if(gettype($message) == "object")
            $message = json_encode($message);
        http_response_code($code);
        $data = debug_backtrace()[0];
        if($requestMethod != "HEAD")
            echo json_encode(array_combine(["message", "file", "line"], [$message, $data["file"], $data["line"]]));
        else
            header("Content-Length: " . strlen(json_encode(array_combine(["message", "file", "line"], [$message, $data["file"], $data["line"]]))));
        die();
    }
?>