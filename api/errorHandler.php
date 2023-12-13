<?php
    register_shutdown_function(function ()
    {
        $error = error_get_last();
        if($error != null)
        {
            unset($error["type"]);
            echo json_encode($error);
            databaseClose();
            die();
        }
    });
    set_error_handler(function ($error_level, $error_message, $error_file, $error_line)
    {
        echo json_encode([
            "message" => $error_message,
            "file" => $error_file,
            "line" => $error_line
        ]);
        databaseClose();
        die();
    });
    ini_set('display_errors', false);
?>