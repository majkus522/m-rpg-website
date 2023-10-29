<?php
    $limit = 50;
    $offset = 0;
    $header = getHeader("Items-Count");
    if($header != false)
    {
        if(!is_numeric($header) || $header < 1)
            exitApi(400, "Incorrect header Items-Count");
        $limit = (int)$header;
    }
    $header = getHeader("Items-Offset");
    if($header != false)
    {
        if(!is_numeric($header) || $header < 0)
            exitApi(400, "Incorrect header Items-Offset");
        $offset = (int)$header;
    }
?>