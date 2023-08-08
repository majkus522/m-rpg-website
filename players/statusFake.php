<?php
    $result = callApi("../api/endpoints/players/$part[$urlIndex]", "GET", array("Session-Key: $_COOKIE[session]", "Session-Type: website"));
    $fake = callApi("../api/endpoints/fake-status/$part[$urlIndex]", "GET", array("Session-Key: $_COOKIE[session]", "Session-Type: website"));
    $fakeExists = $fake->code >= 200 && $fake->code < 300;
    if($fakeExists)
        $fake = $fake->content;
    else
        $fake = $result->content;
?>        
        <title>M-RPG - <?php echo $part[$urlIndex]; ?> - Fake Status</title>
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
                    <p>STR: <?php echo $result->content->strength ?></p>
                    <p>AGL: <?php echo $result->content->agility ?></p>
                    <p>CHR: <?php echo $result->content->charisma ?></p>
                    <p>INT: <?php echo $result->content->intelligence ?></p>
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
                        STR:
                        <input type="number" value="<?php echo $fake->strength ?>" data-stat="strength" data-init="<?php echo $fake->strength ?>">
                    </stat>
                    <stat>
                        AGL:
                        <input type="number" value="<?php echo $fake->agility ?>" data-stat="agility" data-init="<?php echo $fake->agility ?>">
                    </stat>
                    <stat>
                        CHR:
                        <input type="number" value="<?php echo $fake->charisma ?>" data-stat="charisma" data-init="<?php echo $fake->charisma ?>">
                    </stat>
                    <stat>
                        INT:
                        <input type="number" value="<?php echo $fake->intelligence ?>" data-stat="intelligence" data-init="<?php echo $fake->intelligence ?>">
                    </stat>
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