<?php
    $result = callApi("../api/endpoints/players/$_COOKIE[username]", "GET", array("Session-Key: $_COOKIE[session]", "Session-Type: website"));
    $fake = callApi("../api/endpoints/fake-status/$_COOKIE[username]", "GET", array("Session-Key: $_COOKIE[session]", "Session-Type: website"));
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