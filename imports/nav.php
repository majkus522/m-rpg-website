<nav>
            <div><a>Lorem.</a></div>
            <div><a>Lorem.</a></div>
            <div><a>Lorem.</a></div>
            <?php
                if($validLogin)
                {
                    ?>
<div><a href="../players/<?php echo $_COOKIE["username"]; ?>">Player</a>
                <div><a href="../logout/">Logout</a></div>
            </div>
        <?php
                }
                else
                {
                    ?>
<div><a href="../login/">Login</a>
                <div><a href="../register/">Register</a></div>
            </div>
        <?php
                }
            ?>
</nav>
