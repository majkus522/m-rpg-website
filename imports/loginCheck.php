<?php
    require "common.php";
    $validLogin = false;
    if(isset($_COOKIE["session-key"]) && strlen($_COOKIE["session-key"]) > 0 && isset($_COOKIE["session-id"]) && strlen($_COOKIE["session-id"]) > 0 && isset($_COOKIE["username"]) && strlen($_COOKIE["username"]) > 0)
    {
        $result = callApi("players/" . $_COOKIE["username"] . "/session", "GET", ["Session-Key: " . $_COOKIE["session-key"], "Session-ID: " . $_COOKIE["session-id"]]);
        if($result->code >= 200 && $result->code < 300)
        {
            $validLogin = true;
        }
        else
            clearCookie();
    }
    else
        clearCookie();

    function clearCookie()
    {
        ?>
<script>
    document.cookie = "session-key=;path=/;max-age=0;";
    document.cookie = "session-id=;path=/;max-age=0;";
    document.cookie = "username=;path=/;max-age=0;";
</script>
<?php
    }
?>