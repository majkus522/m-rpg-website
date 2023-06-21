<?php
    require "../imports/loginCheck.php";
    if(!$validLogin)
    {
        header("Location: ../login/");
        exit();
    }
    $part = explode("/", $_SERVER["REQUEST_URI"]);
    $username = end($part);
    $result = callApi("../api/endpoints/players/" . $username, "GET", array("Password: " . $_COOKIE["password"]));
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>M-RPG - <?php echo $result[0]->username; ?></title>
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
            <items></items>
            <info>
                <h3><?php
                    echo $result[0]->username;
                ?></h3>
                <div>
                    <p>Level</p>
                    <p><?php
                        echo $result[0]->level;
                    ?></p>
                </div>
                <div>
                    <p>Exp</p>
                    <p><?php
                        echo $result[0]->exp;
                    ?>/1</p>
                </div>
            </info>
        </main>
    </body>
</html>