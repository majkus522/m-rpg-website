<?php
    $extraPath = "../";
    require "../imports/loginCheck.php";
    if($validLogin)
    {
        header("Location: ../players/");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>M-RPG - Register</title>
        <script src="scripts/main.js" defer></script>
        <script src="../imports/passwordHide.js" defer></script>
        <script src="../imports/elements/MrpgCheckbox.js" defer></script>
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
                <label>
                    Email
                    <input type="email">
                </label>
                <label class="password">
                    Password
                    <input type="password">
                </label>
                <label class="password">
                    Repeat password
                    <input type="password">
                </label>
                <mrpg-checkbox class="show">Show password</mrpg-checkbox>
                <mrpg-checkbox class="remember">Remember me</mrpg-checkbox>
                <input type="button" value="Register">
                <p class="error"></p>
            </form>
        </main>
        <?php
            require "../imports/footer.html";
        ?>
    </body>
</html>