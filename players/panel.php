<?php
            $result = callApi("../api/endpoints/players/" . $part[$urlIndex], "GET", array("Session-Key: " . $_COOKIE["session"], "Session-Type: website"));
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
                        <bar><fill style="width: <?php
                            echo round($result[0]->exp / levelExp($result[0]->level) * 100, 2);
                        ?>%;"></fill></bar>
                        <p>Exp</p>
                        <p><?php
                            echo $result[0]->exp . " / " . levelExp($result[0]->level);
                        ?></p>
                    </div>
                    <div>
                        <p>Money</p>
                        <p><?php
                            echo $result[0]->money;
                        ?> $</p>
                    </div>
                </info>
            </content>
        </main>
    </body>
</html>