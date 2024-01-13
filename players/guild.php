<?php
    $resultPlayer = callApi("players/$_COOKIE[username]", "GET", ["Session-Key: " . $_COOKIE["session-key"], "Session-ID: " . $_COOKIE["session-id"]])->content;
    $resultGuild = callAPi("guilds/$resultPlayer->guild", "GET");
    $permissionLevel = $resultGuild->content->leader == $resultPlayer->id ? 2 : ($resultGuild->content->vice_leader == $resultPlayer->id ? 1 : 0);
?>   
        <title>M-RPG - <?php echo $_COOKIE["username"]; ?> - Guild</title>
        <script>
            let guild = "<?php echo $resultPlayer->guild; ?>";
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
                        $resultMembers = callApi("guilds/$resultPlayer->guild/members", "GET", ["Session-Key: " . $_COOKIE["session-key"], "Session-ID: " . $_COOKIE["session-id"]]);
                        $index = 1;
                        foreach($resultMembers->content as $element)
                        {
                            $line = $element->username;
                            if($element->type != "member")
                                $line .= "  ( " . ucwords(str_replace("_", " ", $element->type)) . " )";
                            echo <<< END
                    <tr>
                        <td>$index</td>
                        <td>$line</td>
                        <td>
END;
                            if($permissionLevel >= 1 && $element->username != $resultPlayer->username && $element->type != "leader")
                                echo "<button data-player=\"$element->username\">Kick</button>";
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
                    <p>Leader: <?php
                        $filter = "leader";
                        echo array_values(array_filter($resultMembers->content, "filterMember"))[0]->username;
                    ?></p><?php
                        $filter = "vice_leader";
                        $viceLeader = array_values(array_filter($resultMembers->content, "filterMember"));
                        if(!empty($viceLeader))
                            echo "\r\n\t\t\t\t\t<p>Vice Leader: " . $viceLeader[0]->username . "</p>";
                    ?>

                    <p>Members: <?php echo $resultMembers->headers["Return-Count"]; ?></p>
                    <button data-operation="leave">Leave guild</button>
<?php
                        if($permissionLevel == 2)
                        {
                            echo "\t\t\t\t\t<button data-operation=\"delete\">Delete guild</button>\r\n";
                            echo "\t\t\t\t\t<button data-operation=\"leader\">Select new leader</button>\r\n";
                        }
                        if($permissionLevel >= 1)
                            echo "\t\t\t\t\t<button data-operation=\"vice\">Select new vice leader</button>\r\n";
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
<?php
    function filterMember($input)
    {
        global $filter;
        return $input->type == $filter;
    }
?>