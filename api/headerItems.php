<?php
    $header = getHeader("Items");
    $limit = 50;
    $offset = 0;
    if($header != false)
    {
        if(str_contains($header, "-"))
        {
            $part = explode("-", $header);
            $offset = (int)$part[0];
            $limit = (int)$part[1];
        }
        else
            $limit = (int)$header;
    }
?>