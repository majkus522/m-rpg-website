<?php
    require "common.php";
    $validLogin = false;
    if(isset($_COOKIE["username"]) && strlen($_COOKIE["username"]) > 0 && isset($_COOKIE["session"]) && strlen($_COOKIE["session"]) > 0)
    {
        $result = callApi("../api/endpoints/players/" . $_COOKIE["username"] . "/session", "GET", array("Session-Key: " . $_COOKIE["session"], "Session-Type: website"));
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
    document.cookie = "session=;path=/;max-age=0;";
    document.cookie = "username=;path=/;max-age=0;";
</script>
<?php
    }
?>