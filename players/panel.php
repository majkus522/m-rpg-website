<?php
    $result = callApi("players/$_COOKIE[username]", "GET", ["Session-Key: " . $_COOKIE["session-key"], "Session-ID: " . $_COOKIE["session-id"]]);
    $playerStats = json_decode(file_get_contents("../api/data/playerStats.json"));
?>
        <title>M-RPG - <?php echo $result->content->username; ?></title>
        <script src="../imports/elements/MrpgBar.js" defer></script>
        <script src="scripts/panel.js" defer></script>
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
                <items>
                    <img <?php 
                        if($result->content->helmet != null)
                            echo 'src="../img/equipment/' . $result->content->helmet . '.png"';
                    ?>>
                    <img <?php 
                        if($result->content->chestplate != null)
                            echo 'src="../img/equipment/' . $result->content->chestplate . '.png"';
                    ?>>
                    <img <?php 
                        if($result->content->leggings != null)
                            echo 'src="../img/equipment/' . $result->content->leggings . '.png"';
                    ?>>
                    <img <?php 
                        if($result->content->boots != null)
                            echo 'src="../img/equipment/' . $result->content->boots . '.png"';
                    ?>>
                </items>
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
                        <p>Class</p>
                        <p><?php
                            if($result->content->clazz == null)
                                echo "None";
                            else
                                echo json_decode(file_get_contents("../api/data/classes/" . $result->content->clazz . ".json"))->label;
                        ?></p>
                    </stat>
                    <stat>
                        <p>Level</p>
                        <p><?php
                            echo $result->content->level;
                        ?></p>
                    </stat>
                    <stat>
                        <mrpg-bar><?php echo round($result->content->exp / levelExp($result->content->level) * 100, 2); ?></mrpg-bar>
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
                    <stat>
                        <p>Max mana</p>
                        <p><?php
                            echo $result->content->intl * 3 + 10;
                        ?></p>
                    </stat>
<?php
                        if($result->content->guild != null)
                        {
                            $guild = callApi("guilds/" . $result->content->guild, "GET")->content->name;
                            echo <<< END
                    <stat>
                        <p>Guild</p>
                        <p>$guild</p>
                    </stat>

END;
                        }
                    ?>
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