<?php
    if(str_ends_with($_SERVER["REQUEST_URI"], "/"))
    {
        require "list.php";
    }
    else
    {
        require "panel.php";
    }
?>