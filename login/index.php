<?php
    $extraPath = "..";
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
        <title>M-RPG - Login</title>
        <script src="scripts/main.js" defer></script>
        <script src="../imports/passwordHide.js" defer></script>
        <script src="../imports/elements/MrpgCheckbox.js" defer></script>
        <link href="styles/main.css" rel="stylesheet" type="text/css">
        <link href="../styles/main.css" rel="stylesheet" type="text/css">
    </head>
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
                <mrpg-checkbox class="show">Show password</mrpg-checkbox>
                <mrpg-checkbox class="remember">Remember me</mrpg-checkbox>
                <div class="loading"><div></div></div>
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