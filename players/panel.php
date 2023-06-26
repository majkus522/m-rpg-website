<?php
            $result = callApi("../api/endpoints/players/" . $part[$urlIndex], "GET", array("Password: " . $_COOKIE["password"]));
?>
        <title>M-RPG - <?php echo $result[0]->username; ?></title>
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
            </content>
        </main>
    </body>
</html>