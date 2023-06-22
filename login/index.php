<?php
    require "../imports/loginCheck.php";
    if($validLogin)
    {
        header("Location: ../players/" . $_COOKIE["username"]);
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>M-RPG - Login</title>
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
        <?php
            require "../imports/nav.php";
        ?>
        <main>
            <form>
                <label class="username">
                    Username
                    <input type="text">
                </label>
                <label class="password">
                    Password
                    <input type="password">
                </label>
                <label class="show">
                    Show password
                    <input type="checkbox">
                </label>
                <label class="remember">
                    <input type="checkbox">
                    <span><div></div></span>
                    Remember me
                </label>
                <input type="button" value="Login">
                <p class="error"></p>
                <a href="recovery">Reset password</a>
            </form>
        </main>
        <?php
            require "../imports/footer.html";
        ?>
    </body>
</html>
<script src="scripts/main.js"></script>
<script src="scripts/passwordHide.js"></script>