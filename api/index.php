<?php
    require "errorHandler.php";
    require "../imports/common.php";
    require "databaseController.php";
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
            $urlIndex = $index + 1;
            break;
        }
    }

    $endpoints = glob("endpoints/*.php");
    if(!isset($requestUrlPart[$urlIndex]))
    {
        for($index = 0; $index < sizeof($endpoints); $index++)
            $endpoints[$index] = str_replace("endpoints/", "", str_replace(".php", "", $endpoints[$index]));
       echo json_encode(["available-endpoints" => $endpoints]);
        exit();
    }
    if(in_array("endpoints/" . $requestUrlPart[$urlIndex] . ".php", $endpoints))
    {
        databaseOpen();
        require "endpoints/" . $requestUrlPart[$urlIndex] . ".php";
    }
    else
        exitApi(404, "Unknown endpoint");

    databaseClose();

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
        databaseClose();
        die();
    }

    function clearRequestUrl():string
    {
        $url = str_replace("?" . $_SERVER["QUERY_STRING"], "", $_SERVER["REQUEST_URI"]);
        if(str_ends_with($url, "/"))
            return substr($url, 0, strlen($url) - 1);
        return $url;
    }
?>