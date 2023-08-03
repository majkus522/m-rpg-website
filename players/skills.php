<?php
    $result = callApi("../api/endpoints/players/" . $part[$urlIndex], "GET", array("Session-Key: " . $_COOKIE["session"], "Session-Type: website"));
?>
        <title>M-RPG - <?php echo $result[0]->username; ?> - Skills</title>
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
                <filters>
                    <rarities>
                        <div class="active">Common</div>
                        <div class="active">Extra</div>
                        <div class="active">Unique</div>
                        <div class="active">Ultimate</div>
                    </rarities>
                    <select>
                        <option value="default" selected>Default</option>
                        <option value="rarity">Rarity - Ascending</option>
                        <option value="rarity-desc">Rarity - Descending</option>
                    </select>
                    <button class="search">Search</button>
                </filters>
                <result>
                    <skills>
                    </skills>
                    <inspector>
                        <img>
                        <h2></h2>
                        <p></p>
                    </inspector>
                </result>
            </content>
        </main>
        <?php
            require "../imports/footer.html";
        ?>
    </body>
</html>
<script src="../imports/scripts.js"></script>
<script src="scripts/skills.js"></script>