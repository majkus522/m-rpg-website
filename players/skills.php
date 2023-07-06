<?php
    $result = callApi("../api/endpoints/players/" . $part[$urlIndex], "GET", array("Password: " . $_COOKIE["password"]));
?>
        <title>M-RPG - <?php echo $result[0]->username; ?> - Settings</title>
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
                <skills>
                    <?php
                        $result = callApi("../api/endpoints/skills/" . $result[0]->username, "GET", array("Password: " . $_COOKIE["password"]));
                        if($result[1] == 404)
                        {
                            echo "<p>You don't have any skills</p>";
                        }
                        else
                        {
                            $first = true;
                            foreach($result[0] as $skill)
                            {
                                if(!$first)
                                    echo "\t\t\t\t\t";
                                echo '<skill data-skill="' . $skill->skill . '"><img src="../img/skills/' . $skill->skill . '.png"></skill>' . "\r\n";
                                $first = false;
                            }
                        }
                    ?>
                </skills>
                <inspector>
                    <img>
                    <h2></h2>
                    <p></p>
                </inspector>
            </content>
        </main>
    </body>
</html>
<script src="scripts/skills.js"></script>