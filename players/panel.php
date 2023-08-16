<?php
    $result = callApi("../api/endpoints/players/$part[$urlIndex]", "GET", array("Session-Key: $_COOKIE[session]", "Session-Type: website"));
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
                                    echo $result->content->int;
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
    let str = <?php echo $result->content->str; ?>;
    let agl = <?php echo $result->content->agl; ?>;
    let chr = <?php echo $result->content->chr; ?>;
    let int = <?php echo $result->content->int; ?>;
</script>
<script src="scripts/panel.js"></script>