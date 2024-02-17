<?php
    $extraPath = "..";
    require "../imports/loginCheck.php";

    $requestUrlPart = explode("/", clearRequestUrl());
    $urlIndex = 0;
    for($index = 0; $index < sizeof($requestUrlPart); $index++)
    {
        if(strtolower($requestUrlPart[$index]) == "forum")
        {
            $urlIndex = $index + 1;
            break;
        }
    }
    $type = isset($requestUrlPart[$urlIndex]) ? "topic" : "main"
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>M-RPG - Forum</title>
        <link href="styles/<?php echo $type; ?>.css" rel="stylesheet" type="text/css">
        <link href="../styles/main.css" rel="stylesheet" type="text/css">
        <script src="../imports/scripts.js" defer></script>
    </head>
    <body>
        <?php
            require "../imports/nav.php";
        ?>
        <main>
            <?php
                require $type . ".php";
            ?>
        </main>
        <?php
            require "../imports/footer.html";
        ?>
    </body>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="scripts/<?php echo $type; ?>.js" defer></script>
</html>