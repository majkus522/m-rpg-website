<?php
    $extraPath = "../../";
    require $extraPath . "imports/loginCheck.php";
    $codePresent = isset($_GET["code"]);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>M-RPG - Login</title>
        <script src="../../imports/elements/MrpgCheckbox.js" defer></script>
        <link href="../../styles/main.css" rel="stylesheet" type="text/css">
        <link href="../styles/main.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <?php
            require $extraPath . "imports/nav.php";
        ?>
        <main>
            <?php
                if($codePresent)
                {
            ?><script>
                let code = "<?php echo $_GET["code"]; ?>";
            </script>
            <form>
                <label class="password">
                    Password
                    <input type="password">
                </label>
                <label class="password">
                    Repeat password
                    <input type="password">
                </label>
                <mrpg-checkbox class="show">Show password</mrpg-checkbox>
                <div class="loading"><div></div></div>
                <input type="button" value="Send">
                <p class="error"></p>
            </form>
        <?php
                }
                else
                {
            ?>
<form>
                <label>
                    Username or email
                    <input type="text">
                </label>
                <div class="loading"><div></div></div>
                <input type="button" value="Send">
                <p class="error"></p>
            </form>
        <?php
                }            
            ?></main>
        <?php
            require $extraPath . "imports/footer.html";
        ?>
    </body>
</html>
<?php
    if($codePresent)
    {
        ?>
<script src="scripts/reset.js"></script>
<script src="../../imports/passwordHide.js"></script>
        <?php
    }
    else
    {
        ?>
<script src="scripts/main.js"></script>
        <?php
    }
?>