<?php
    if(isset($_COOKIE["username"]) && strlen($_COOKIE["username"]) > 0 && isset($_COOKIE["password"]) && strlen($_COOKIE["password"]) > 0)
    {
        require "../api/common.php";
        $result = callApi("../api/endpoints/players/" . $_COOKIE["username"] . "/logged", "GET", ["Password" -> $_COOKIE["password"]]);
    }
    else
    {
        $_COOKIE["username"] = "";
        $_COOKIE["password"] = "";
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <?php
            $first = true;
            foreach(array_merge(glob("../styles/*.css"), glob("styles/*.css")) as $file)
            {
                if(!$first)
                    echo "\t";
                echo '<link href="' . $file . '" rel="stylesheet" type="text/css">' . "\r\n\t";
                $first = false;
            }
        ?></head>
    <body>
        <nav>
            <div><a>Lorem.</a></div>
            <div><a>Lorem.</a></div>
            <div><a>Lorem.</a></div>
            <div><a href="../login/">Login</a>
                <div><a href="../register/">Register</a></div>
            </div>
        </nav>
        <main>
            <form>
                <label>
                    Username
                    <input type="text">
                </label>
                <label>
                    Password
                    <input type="password">
                </label>
                <input type="button" value="Login">
            </form>
        </main>
    </body>
</html>
<script src="scripts/main.js"></script>