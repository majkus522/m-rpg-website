<?php
    require "common.php";
    $validLogin = false;
    if(isset($_COOKIE["username"]) && strlen($_COOKIE["username"]) > 0 && isset($_COOKIE["session"]) && strlen($_COOKIE["session"]) > 0)
    {
        $queryResult = connectToDatabase('select `players`.`id` from `players-sessions`, `players` where `players-sessions`.`player` = `players`.`id` and `players`.`username` = ? and `players-sessions`.`key` = ?', array("ss", $_COOKIE["username"], $_COOKIE["session"]));
        if(empty($queryResult))
        {
            clearCookie();
        }
        else
            $validLogin = true;
    }
    else
        clearCookie();

    function clearCookie()
    {
        ?>
<script>
    document.cookie = "session=;path=/;max-age=0;";
    document.cookie = "username=;path=/;max-age=0;";
</script>
<?php
    }
?>