<?php
    require "common.php";
    $validLogin = false;
    if(isset($_COOKIE["username"]) && strlen($_COOKIE["username"]) > 0 && isset($_COOKIE["password"]) && strlen($_COOKIE["password"]) > 0)
    {
        $result = callApi("../api/endpoints/players/" . $_COOKIE["username"] . "/logged", "GET", array("Password: " . $_COOKIE["password"]));
        if($result[1] == 200 && $result[0])
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
    document.cookie = "username=;path=/;max-age=0;";
    document.cookie = "password=;path=/;max-age=0;";
</script>
<?php
    }
?>