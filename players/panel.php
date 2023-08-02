<?php
    $queryResult = connectToDatabase('select * from `view-players` where `username` = ? limit 1', array("s", $_COOKIE["username"]))[0];
?>
        <title>M-RPG - <?php echo $_COOKIE["username"]; ?></title>
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
                        echo $queryResult->username;
                    ?></h3>
                    <div>
                        <div>
                            <stat>
                                <p>Strength</p>
                                <p><?php
                                    echo $queryResult->strength;
                                ?></p>
                            </stat>
                            <stat>
                                <p>Agility</p>
                                <p><?php
                                    echo $queryResult->agility;
                                ?></p>
                            </stat>
                            <stat>
                                <p>Charisma</p>
                                <p><?php
                                    echo $queryResult->charisma;
                                ?></p>
                            </stat>
                            <stat>
                                <p>Intelligence</p>
                                <p><?php
                                    echo $queryResult->intelligence;
                                ?></p>
                            </stat>
                        </div>
                        <graph>
                            <svg>
                                <polygon/>
                                <line/>
                                <line/>
                            </svg>
                            <p>STR</p>
                            <p>AGL</p>
                            <p>CHR</p>
                            <p>INT</p>
                        </graph>
                    </div>
                    <stat>
                        <p>Level</p>
                        <p><?php
                            echo $queryResult->level;
                        ?></p>
                    </stat>
                    <stat>
                        <bar><fill style="width: <?php
                            echo round($queryResult->exp / levelExp($queryResult->level) * 100, 2);
                        ?>%;"></fill></bar>
                        <p>Exp</p>
                        <p><?php
                            echo $queryResult->exp . " / " . levelExp($queryResult->level);
                        ?></p>
                    </stat>
                    <stat>
                        <p>Money</p>
                        <p><?php
                            echo $queryResult->money;
                        ?> $</p>
                    </stat>
                </info>
            </content>
        </main>
        <?php
            require "../imports/footer.html";
        ?>
    </body>
</html>
<script>
    let str = <?php echo $queryResult->strength; ?>;
    let agl = <?php echo $queryResult->agility; ?>;
    let chr = <?php echo $queryResult->charisma; ?>;
    let int = <?php echo $queryResult->intelligence; ?>;
</script>
<script src="scripts/panel.js"></script>