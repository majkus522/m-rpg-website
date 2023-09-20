<?php
    $result = callApi("../api/endpoints/players/$_COOKIE[username]", "GET", array("Session-Key: $_COOKIE[session]", "Session-Type: website"));
?>
        <title>M-RPG - <?php echo $result->content->username; ?></title>
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
                        echo $result->content->username;
                    ?></h3>
                    <div>
                        <div>
                            <stat>
                                <p>Strength</p>
                                <p><?php
                                    echo $result->content->str;
                                ?></p>
                            </stat>
                            <stat>
                                <p>Agility</p>
                                <p><?php
                                    echo $result->content->agl;
                                ?></p>
                            </stat>
                            <stat>
                                <p>Charisma</p>
                                <p><?php
                                    echo $result->content->chr;
                                ?></p>
                            </stat>
                            <stat>
                                <p>Intelligence</p>
                                <p><?php
                                    echo $result->content->intl;
                                ?></p>
                            </stat>
                            <stat>
                                <p>Defence</p>
                                <p><?php
                                    echo $result->content->def;
                                ?></p>
                            </stat>
                            <stat>
                                <p>Vitality</p>
                                <p><?php
                                    echo $result->content->vtl;
                                ?></p>
                            </stat>
                        </div>
                        <graph>
                            <svg>
                                <polygon/>
                            </svg>
                        </graph>
                    </div>
                    <stat>
                        <p>Level</p>
                        <p><?php
                            echo $result->content->level;
                        ?></p>
                    </stat>
                    <stat>
                        <bar><fill style="width: <?php
                            echo round($result->content->exp / levelExp($result->content->level) * 100, 2);
                        ?>%;"></fill></bar>
                        <p>Exp</p>
                        <p><?php
                            echo $result->content->exp . " / " . levelExp($result->content->level);
                        ?></p>
                    </stat>
                    <stat>
                        <p>Money</p>
                        <p><?php
                            echo $result->content->money;
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
    let stats = [<?php
        echo $result->content->str . ", " . $result->content->agl . ", " . $result->content->chr . ", " . $result->content->intl . ", " . $result->content->def . ", " . $result->content->vtl;
    ?>];
    let labels = ["STR", "AGL", "CHR", "INTL", "DEF", "VTL"];
</script>
<script src="scripts/panel.js"></script>