<?php
    if(!isset($extraPath))
        $extraPath = "";
?>
<header>
            <div class="ham"></div>
            <script src="<?php echo $extraPath; ?>imports/hamburger.js" defer></script>
            <nav>
                <div><a href="<?php echo $extraPath; ?>">Home</a></div>
                <div><a href="<?php echo $extraPath; ?>leaderboard">Leaderboard</a></div>
                <?php
                    if($validLogin)
                    {
                        ?>
    <div><a href="<?php echo $extraPath; ?>players/">Player</a>
                    <div>
                        <?php
                            $apiResult = callApi($extraPath . "api/skills/$_COOKIE[username]/statusFake", "GET", array("Session-Key: $_COOKIE[session]", "Session-Type: website"));
                            if($apiResult->code >= 200 && $apiResult->code < 300)
                            {
                                ?><a href="<?php echo $extraPath; ?>players/?tab=statusFake">Fake Status</a><?php
                                echo "\r\n\t\t\t\t\t";
                            }
                        ?><a href="<?php echo $extraPath; ?>players/?tab=skills">Skills</a>
                        <a href="<?php echo $extraPath; ?>players/?tab=settings">Settings</a>
                        <a href="<?php echo $extraPath; ?>logout/">Logout</a>
                    </div>
                </div>
            <?php
                    }
                    else
                    {
                        ?>
    <div><a href="<?php echo $extraPath; ?>login/">Login</a>
                    <div>
                        <a href="<?php echo $extraPath; ?>register/">Register</a>
                    </div>
                </div>
            <?php
                    }
                ?>
            </nav>
</header>
