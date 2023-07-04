<?php
    $result = callApi("../api/endpoints/players/" . $part[$urlIndex], "GET", array("Password: " . $_COOKIE["password"]));
?>
        <title>M-RPG - <?php echo $result[0]->username; ?> - Settings</title>
    </head>
    <body>
        <?php
            require "../imports/nav.php";
        ?>
        <main>
            <?php
                require "nav.php";
            ?>
            <content>
                <?php
                    $result = callApi("../api/endpoints/skills/" . $result[0]->username, "GET", array("Password: " . $_COOKIE["password"]))[0];
                    $first = true;
                    foreach($result as $skill)
                    {
                        if(!$first)
                            echo "\t\t\t\t";
                        echo "<skill>" . $skill->skill . "</skill>\r\n";
                        $first = false;
                    }
                ?>
            </content>
        </main>
    </body>
</html>