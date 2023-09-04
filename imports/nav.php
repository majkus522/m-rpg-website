<?php
    if(!isset($extraPath))
        $extraPath = "";
?>
<nav>
            <div><a href="<?php echo $extraPath; ?>../">Home</a></div>
            <?php
                if($validLogin)
                {
                    ?>
<div><a href="<?php echo $extraPath; ?>../players/<?php echo $_COOKIE["username"]; ?>">Player</a>
                <div>
                    <?php
                        $apiResult = callApi("../api/endpoints/skills/$part[$urlIndex]/statusFake", "GET", array("Session-Key: $_COOKIE[session]", "Session-Type: website"));
                        if($apiResult->code >= 200 && $apiResult->code < 300)
                        {
                            ?><a href="<?php echo $extraPath; ?>../players/<?php echo $_COOKIE["username"]; ?>?tab=statusFake">Fake Status</a><?php
                            echo "\r\n\t\t\t\t\t";
                        }
                    ?><a href="<?php echo $extraPath; ?>../players/<?php echo $_COOKIE["username"]; ?>?tab=skills">Skills</a>
                    <a href="<?php echo $extraPath; ?>../players/<?php echo $_COOKIE["username"]; ?>?tab=settings">Settings</a>
                    <a href="<?php echo $extraPath; ?>../logout/">Logout</a>
                </div>
            </div>
        <?php
                }
                else
                {
                    ?>
<div><a href="<?php echo $extraPath; ?>../login/">Login</a>
                <div>
                    <a href="<?php echo $extraPath; ?>../register/">Register</a>
                </div>
            </div>
        <?php
                }
            ?>
</nav>
