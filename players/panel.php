<?php
    $result = callApi("../api/endpoints/players/$_COOKIE[username]", "GET", array("Session-Key: $_COOKIE[session]", "Session-Type: website"));
    $playerStats = json_decode(file_get_contents("../api/data/playerStats.json"));
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
<?php
                                $shortLabels = "";
                                $values = "";
                                $first = true;

                                foreach($playerStats as $element)
                                {
                                    if(!$first)
                                    {
                                        $shortLabels .= ", ";
                                        $values .= ", ";
                                    }
                                    $short = $element->short;
                                    $shortLabels .= '"' . strtoupper($short) . '"';
                                    $value = $result->content->$short;
                                    $values .= $value;
                                    $first = false;
                                    echo <<< END
                            <stat>
                                <p>$element->label</p>
                                <p>$value</p>
                            </stat>

END;
                                }
                            ?>
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
    let stats = [<?php echo $values; ?>];
    let labels = [<?php echo $shortLabels; ?>];
</script>
<script src="scripts/panel.js"></script>