<?php
    $limit = 50;
    $offset = 0;
    $header = getHeader("Result-Count");
    if($header != false)
    {
        if(!is_numeric($header) || $header < 1)
            exitApi(400, "Incorrect header Result-Count");
        $limit = (int)$header;
    }
    $header = getHeader("Result-Offset");
    if($header != false)
    {
        if(!is_numeric($header) || $header < 0)
            exitApi(400, "Incorrect header Result-Offset");
        $offset = (int)$header;
    }
?>