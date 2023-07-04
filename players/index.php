<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="../styles/main.css" rel="stylesheet" type="text/css">
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
            if($_COOKIE["username"] != $part[$urlIndex])
            {
                header("Location: " . $_COOKIE["username"]);
                exit();
            }
            if(!isset($_GET["tab"]))
                $file = "panel";
            else
            {
                switch($_GET["tab"])
                {
                    case "settings":
                        $file = "settings";
                        break;

                    case "skills":
                        $file = "skills";
                        break;
            
                    default:
                    $file = "panel";
                        break;
                }
            }
            echo '<link href="styles/' . $file . '.css" rel="stylesheet" type="text/css">' . "\r\n";
            require $file . ".php";
        ?>