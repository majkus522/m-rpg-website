<?php
    if(!isset($extraPath))
        $extraPath = ".";
?>
<header>
            <div class="ham">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <script src="<?php echo $extraPath; ?>/imports/hamburger.js" defer></script>
            <nav>
                <div><a href="<?php echo $extraPath; ?>/">Home</a></div>
                <div><a href="<?php echo $extraPath; ?>/leaderboard">Leaderboard</a></div>
<?php
                    if($validLogin)
                    {
                        echo <<< END
                <div>
                    <a href="$extraPath/players/">Player</a>

END;
                        $apiResult = callApi("players/$_COOKIE[username]", "GET", ["Session-Key: $_COOKIE[session]", "Session-Type: website"]);
                        if($apiResult->content->guild != null)
                            echo "\t\t\t\t\t<a href=\"$extraPath/players/?tab=guild\">Guild</a>\r\n";
                        $apiResult = callApi("skills/$_COOKIE[username]/statusFake", "GET", array("Session-Key: $_COOKIE[session]", "Session-Type: website"));
                        if($apiResult->code >= 200 && $apiResult->code < 300)
                            echo "\t\t\t\t\t<a href=\"$extraPath/players/?tab=statusFake\">Fake Status</a>\r\n";
                        echo <<< END
                    <a href="$extraPath/players/?tab=skills">Skills</a>
                    <a href="$extraPath/players/?tab=settings">Settings</a>
                    <a href="$extraPath/logout/">Logout</a>
                </div>

END;
                    }
                    else
                    {
                        echo <<< END
                <div>
                    <a href="$extraPath/login">Login</a>
                    <a href="$extraPath/register">Register</a>
                </div>
END;
                    }
                ?>
            </nav>
        </header>
