<?php
    function errorHandler():void
    {
        global $requestMethod;
        if (error_reporting())
        {
            http_response_code(500);
            $values = func_get_args();
            while(sizeof($values) > 4)
                array_pop($values);
            array_shift($values);
            $values[1] = str_replace("\\", "/", $values[1]);
            $response = array_combine(["message", "file", "line"], $values);
            $result = json_encode($response);
            header("Content-Length: " . strlen($result));
            if($requestMethod != "HEAD")
                echo $result;
            die();
        }
        databaseClose();
    }

    function parseHandler():void
    {
        global $requestMethod;
        $lasterror = error_get_last();
        if ( $lasterror == null )
            return;
        http_response_code(500);
        if(str_starts_with($lasterror["message"], "Uncaught Exception: "))
        {
            $message = str_replace("Uncaught Exception: ", "", $lasterror["message"]);
            $part = explode(".", $message);
            $partFile = explode(" ", $part[0]);
            $file = end($partFile) . ".php";
            $line = str_replace("php:", "", explode("\n", $part[1])[0]);
            $response = array_combine(["message", "file", "line"], [explode(":", str_replace(" in " . $file, "", $message))[0], $file, $line]);
        }
        else
            $response = array_combine(["code", "message", "file", "line"], [$lasterror['type'], $lasterror['message'], str_replace("\\", "/", $lasterror['file']), $lasterror['line']]);
        $result = json_encode($response);
        header("Content-Length: " . strlen($result));
        if($requestMethod != "HEAD")
            echo $result;
        databaseClose();
        die();
    }

    ini_set('display_errors', false);
    set_error_handler("errorHandler", E_ALL);
    register_shutdown_function("parseHandler");
?>