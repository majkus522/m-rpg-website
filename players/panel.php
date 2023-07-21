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
                        <div>
                            <stat>
                                <p>Strength</p>
                                <p><?php
                                    echo $result[0]->strength;
                                ?></p>
                            </stat>
                            <stat>
                                <p>Agility</p>
                                <p><?php
                                    echo $result[0]->agility;
                                ?></p>
                            </stat>
                            <stat>
                                <p>Charisma</p>
                                <p><?php
                                    echo $result[0]->charisma;
                                ?></p>
                            </stat>
                            <stat>
                                <p>Intelligence</p>
                                <p><?php
                                    echo $result[0]->intelligence;
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
                            echo $result[0]->level;
                        ?></p>
                    </stat>
                    <stat>
                        <bar><fill style="width: <?php
                            echo round($result[0]->exp / levelExp($result[0]->level) * 100, 2);
                        ?>%;"></fill></bar>
                        <p>Exp</p>
                        <p><?php
                            echo $result[0]->exp . " / " . levelExp($result[0]->level);
                        ?></p>
                    </stat>
                    <stat>
                        <p>Money</p>
                        <p><?php
                            echo $result[0]->money;
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
    let str = <?php echo $result[0]->strength; ?>;
    let agl = <?php echo $result[0]->agility; ?>;
    let chr = <?php echo $result[0]->charisma; ?>;
    let int = <?php echo $result[0]->intelligence; ?>;
</script>
<script src="scripts/panel.js"></script>