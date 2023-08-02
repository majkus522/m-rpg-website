<?php
    $queryResult = connectToDatabase('select * from `view-players` where `username` = ? limit 1', array("s", $_COOKIE["username"]))[0];
?>
        <title>M-RPG - <?php echo $_COOKIE["username"]; ?> - Settings</title>
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
                <div class="email">
                    Email
                    <input type="email" value="<?php echo $queryResult->email; ?>">
                    <input type="button" value="Update">
                    <p class="error"></p>
                </div>
                <div class="password">
                    Password
                    <input type="password" placeholder="Password">
                    <label class="show">
                        Show password
                        <input type="checkbox">
                    </label>
                    <input type="button" value="Update">
                    <p class="error"></p>
                </div>
                <div class="delete">
                    Delete account
                    <input type="button" value="Delete">
                    <p class="error"></p>
                </div>
            </content>
        </main>
        <?php
            require "../imports/footer.html";
        ?>
    </body>
</html>
<script src="scripts/settings.js"></script>
<script src="scripts/passwordHide.js"></script>
<script src="../imports/scripts.js"></script>