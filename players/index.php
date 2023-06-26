<?php
    require "../imports/loginCheck.php";
    if(!$validLogin)
    {
        header("Location: ../login/");
        exit();
    }
    $part = explode("/", $_SERVER["REDIRECT_URL"]);
    for($index = 0; $index < sizeof($part); $index++)
    {
        if($part[$index] == "players")
        {
            $urlIndex = $index + 1;
            break;
        }
    }
    if(!isset($_GET["tab"]))
    {
        require "panel.php";
        exit();
    }
    switch($_GET["tab"])
    {
        case "settings":
            require "settings.php";
            break;

        default:
            require "panel.php";
            break;
    }
?>