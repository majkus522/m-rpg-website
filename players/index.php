<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="../styles/main.css" rel="stylesheet" type="text/css">
        <?php
            $extraPath = "..";
            require "../imports/loginCheck.php";
            if(!$validLogin)
            {
                header("Location: ../login/");
                exit();
            }
            $file = "panel";
            if(isset($_GET["tab"]))
            {
                switch($_GET["tab"])
                {
                    case "settings":
                    case "skills":
                        $file = $_GET["tab"];
                        break;
                        
                    case "guild":
                        $apiResult = callApi("players/$_COOKIE[username]", "GET", ["Session-Key: " . $_COOKIE["session-key"], "Session-ID: " . $_COOKIE["session-id"]]);
                        if($apiResult->content->guild != null)
                            $file = "guild";
                        break;

                    case "statusFake":
                        $apiResult = callApi("skills/$_COOKIE[username]/statusFake", "GET", ["Session-Key: " . $_COOKIE["session-key"], "Session-ID: " . $_COOKIE["session-id"]]);
                        if($apiResult->code >= 200 && $apiResult->code < 300)
                            $file = "statusFake";
                        break;
                }
            }
            echo '<link href="styles/' . $file . '.css" rel="stylesheet" type="text/css">' . "\r\n";
            require $file . ".php";
        ?>