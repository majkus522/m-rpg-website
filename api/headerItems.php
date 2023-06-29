<?php
    $header = getHeader("Items");
    $limit = 50;
    $offset = 0;
    if($header != false)
    {
        if(str_contains($header, "-"))
        {
            $part = explode("-", $header);
            if(!is_numeric($part[0]) || !is_numeric($part[1]))
                exitApi(400, "Incorect header (Items)");
            $offset = (int)$part[0];
            $limit = (int)$part[1];
        }
        else
        {
            if(!is_numeric($header))
                exitApi(400, "Incorect header (Items)");
            $limit = (int)$header;
        }
    }
    if($limit < 1 || $offset < 0)
        exitApi(400, "Incorect header (Items)");
?>