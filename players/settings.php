<?php
    $result = callApi("players/$_COOKIE[username]", "GET", ["Session-Key: " . $_COOKIE["session-key"], "Session-ID: " . $_COOKIE["session-id"]]);
?>
        <title>M-RPG - <?php echo $result->content->username; ?> - Settings</title>
        <script src="scripts/settings.js" defer></script>
        <script src="../imports/passwordHide.js" defer></script>
        <script src="../imports/scripts.js" defer></script>
        <script src="../imports/elements/MrpgCheckbox.js" defer></script>
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
                    <input type="email" value="<?php echo $result->content->email; ?>" data-old="<?php echo $result->content->email; ?>">
                    <div class="loading"><div></div></div>
                    <input type="button" value="Update">
                    <p class="error"></p>
                </div>
                <div class="password">
                    Password
                    <input type="password" placeholder="New password" autocomplete="new-password">
                    <mrpg-checkbox class="show">Show password</mrpg-checkbox>
                    <div class="loading"><div></div></div>
                    <input type="button" value="Update">
                    <p class="error"></p>
                </div>
                <div class="delete">
                    Delete account
                    <div class="loading"><div></div></div>
                    <input type="button" value="Delete">
                    <p class="error"></p>
                </div>
            </content>
            <dialog>
                <label>
                    Enter password:
                    <input type="password">
                </label>
                <button>Confirm</button>
                <button>Cancel</button>
            </dialog>
        </main>
        <?php
            require "../imports/footer.html";
        ?>
    </body>
</html>