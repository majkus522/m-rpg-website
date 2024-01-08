<?php
    $result = callApi("players/$_COOKIE[username]", "GET", ["Session-Key: " . $_COOKIE["session-key"], "Session-ID: " . $_COOKIE["session-id"]]);
    $fake = callApi("fake-status/$_COOKIE[username]", "GET", ["Session-Key: " . $_COOKIE["session-key"], "Session-ID: " . $_COOKIE["session-id"]]);
    $playerStats = json_decode(file_get_contents("../api/data/playerStats.json"));
    $fakeExists = $fake->code >= 200 && $fake->code < 300;
    if($fakeExists)
        $fake = $fake->content;
    else
        $fake = $result->content;
?>        
        <title>M-RPG - <?php echo $_COOKIE["username"]; ?> - Fake Status</title>
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
                <div class="real">
                    <h2>Real</h2>
                    <p>Level: <?php echo $result->content->level ?></p>
                    <p>Class: <?php
                        if($result->content->clazz == null)
                            echo "None";
                        else
                            echo json_decode(file_get_contents("../api/data/classes/" . $result->content->clazz . ".json"))->label;
                    ?></p>
                    <p>Money: <?php echo $result->content->money ?> $</p>
<?php
                        foreach($playerStats as $element)
                        {
                            $short = $element->short;
                            echo "\t\t\t\t\t<p>" . strtoupper($short) . ": " . $result->content->$short . "</p>\r\n";
                        }
                    ?>
                </div>
                <div class="fake">
                    <h2>Fake</h2>
                    <stat>
                        Level:
                        <input type="number" value="<?php echo $fake->level ?>" data-stat="level" data-init="<?php echo $fake->level ?>">
                    </stat>
                    <stat>
                        Money:
                        <input type="number" value="<?php echo $fake->money ?>" data-stat="money" data-init="<?php echo $fake->money ?>">
                    </stat>
                    <stat>
                        Class:
                        <select>
                            <option value="none"<?php
                                if($fake->clazz == null)
                                    echo " selected";
                            ?>>None</option>
<?php
                                foreach(glob("../api/data/classes/*.json") as $element)
                                {
                                    $simple = str_replace(".json", "", explode("/", $element)[4]);
                                    echo "\t\t\t\t\t\t\t" . '<option value="' . $simple . '"';
                                    if($fake->clazz == $simple)
                                        echo " selected";
                                    echo ">" . json_decode(file_get_contents($element))->label . "</option>\r\n";
                                }
                            ?>
                        </select>
                    </stat>
<?php
                        foreach($playerStats as $element)
                        {
                            $short = $element->short;
                            $value = $fake->$short;
                            $upper = strtoupper($short);
                            echo <<< END
                    <stat>
                        $upper:
                        <input type="number" value="$value" data-stat="$short" data-init="$value">
                    </stat>

END;
                        }
                    ?>
                    <div>
                        <p class="error"></p>
                        <button>Save</button>
                    </div>
                </div>
            </content>
        </main>
        <?php
            require "../imports/footer.html";
        ?>
    </body>
</html>
<script>
    let fakeExists = <?php echo $fakeExists ? "true" : "false"; ?>;
</script>
<script src="../imports/scripts.js"></script>
<script src="scripts/statusFake.js"></script>