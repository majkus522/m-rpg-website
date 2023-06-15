<?php
    $validLogin = false;
    if(isset($_COOKIE["username"]) && strlen($_COOKIE["username"]) > 0 && isset($_COOKIE["password"]) && strlen($_COOKIE["password"]) > 0)
    {
        require "../imports/common.php";
        $result = callApi("../api/endpoints/players/" . $_COOKIE["username"] . "/logged", "GET", array("Password: " . $_COOKIE["password"]));
        if($result[1] == 200 && $result[0])
        {
            $validLogin = true;
            header("Location: ../players/" . $_COOKIE["username"]);
            exit();
        }
        else
            clearCookie();
    }
    else
        clearCookie();

    function clearCookie()
    {
        ?>
<script>
    document.cookie = "username=;path=/;max-age=0;";
    document.cookie = "password=;path=/;max-age=0;";
</script>
        <?php
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
        <?php
            require "../imports/nav.php";
        ?>
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
                <label>
                    <input type="checkbox">
                    Remember me
                </label>
                <input type="button" value="Login">
                <p class="error"></p>
            </form>
        </main>
        <?php
            require "../imports/footer.html";
        ?>
    </body>
</html>
<script src="scripts/main.js"></script>