<?php
    $resultPlayer = callApi("players/$_COOKIE[username]", "GET", ["Session-Key: $_COOKIE[session]", "Session-Type: website"])->content;
    $resultGuild = callAPi("guilds/$resultPlayer->guild", "GET");
    $isLeader = $resultGuild->content->leader == $resultPlayer->id;
?>   
        <title>M-RPG - <?php echo $_COOKIE["username"]; ?> - Guild</title>
        <script>
            let guild = "<?php echo $resultPlayer->guild; ?>";
            let isLeader = <?php echo $isLeader ? "true" : "false"; ?>;
        </script>
        <script src="../imports/scripts.js" defer></script>
        <script src="scripts/guild.js" defer></script>
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
                <table>
<?php
                        $resultMembers = callApi("guilds/$resultPlayer->guild/members", "GET", ["Session-Key: $_COOKIE[session]", "Session-Type: website"]);
                        $index = 1;
                        foreach($resultMembers->content as $element)
                        {
                            $fsdf = "";
                            echo <<< END
                    <tr>
                        <td>$index</td>
                        <td>$element</td>
                        <td>
END;
                            if($isLeader && $element != $resultPlayer->username)
                                echo "<button data-kick=\"$element\">Kick</button>";
                            echo <<< END
</td>
                    </tr>

END;
                            $index++;
                        }
                    ?>
                </table>
                <div>
                    <h2><?php echo $resultGuild->content->name; ?></h2>
                    <p>Leader: <?php echo $resultMembers->content[0]; ?></p>
                    <p>Members: <?php echo $resultMembers->headers["Return-Count"]; ?></p>
                    <button>Leave guild</button>
<?php
                        if($isLeader)
                        {
                            echo "\t\t\t\t\t<button>Delete guild</button>\r\n";
                        }
                    ?>
                    <p class="error"></p>
                </div>
            </content>
        </main>
        <?php
            require "../imports/footer.html";
        ?>
    </body>
</html>