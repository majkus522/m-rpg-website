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
                <div class="loading"><div></div></div>
                <input type="button" value="Register">
                <p class="error"></p>
            </form>
        </main>
        <?php
            require "../imports/footer.html";
        ?>
    </body>
</html>