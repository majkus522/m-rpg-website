<?php
    if(!isset($extraPath))
        $extraPath = "";
?>
<nav>
            <div><a>Lorem.</a></div>
            <div><a>Lorem.</a></div>
            <div><a>Lorem.</a></div>
            <?php
                if($validLogin)
                {
                    ?>
<div><a href="<?php echo $extraPath; ?>../players/<?php echo $_COOKIE["username"]; ?>">Player</a>
                <div><a href="<?php echo $extraPath; ?>../logout/">Logout</a></div>
            </div>
        <?php
                }
                else
                {
                    ?>
<div><a href="<?php echo $extraPath; ?>../login/">Login</a>
                <div><a href="<?php echo $extraPath; ?>../register/">Register</a></div>
            </div>
        <?php
                }
            ?>
</nav>
