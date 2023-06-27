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
                <div class="email">
                    Email
                    <input type="email" value="<?php echo $result[0]->email; ?>">
                    <input type="button" value="Update">
                    <p></p>
                </div>
                <div class="password">
                    Password
                    <input type="password" placeholder="Password">
                    <label class="show">
                        Show password
                        <input type="checkbox">
                    </label>
                    <input type="button" value="Update">
                    <p></p>
                </div>
                <div class="delete">
                    Delete account
                    <input type="button" value="Delete">
                    <p></p>
                </div>
            </content>
        </main>
    </body>
</html>
<script src="scripts/settings.js"></script>
<script src="scripts/passwordHide.js"></script>