<?php
    $extraPath = "../";
    require $extraPath . "../imports/loginCheck.php";
    $codePresent = isset($_GET["code"]);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>M-RPG - Login</title>
        <?php
            $first = true;
            foreach(array_merge(glob($extraPath . "../styles/*.css"), glob("styles/*.css")) as $file)
            {
                if(!$first)
                    echo "\t";
                echo '<link href="' . $file . '" rel="stylesheet" type="text/css">' . "\r\n\t";
                $first = false;
            }
        ?></head>
    <body>
        <?php
            require $extraPath . "../imports/nav.php";
        ?>
        <main>
            <?php
                if($codePresent)
                {
            ?><script>
                let code = "<?php echo $_GET["code"]; ?>";
            </script>
            <form>
                <label>
                    Password
                    <input type="password">
                </label>
                <label>
                    Repeat password
                    <input type="password">
                </label>
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
                <input type="button" value="Send">
                <p class="error"></p>
            </form>
            <?php
                }            
            ?>
        </main>
        <?php
            require $extraPath . "../imports/footer.html";
        ?>
    </body>
</html>
<?php
    if($codePresent)
    {
        ?>
<script src="scripts/reset.js"></script>
        <?php
    }
    else
    {
        ?>
<script src="scripts/main.js"></script>
        <?php
    }
?>