<?php
    function isSingleGet():bool
    {
        global $requestUrlPart;
        global $urlIndex;
        return sizeof($requestUrlPart) > ($urlIndex + 1);
    }

    function getHeader(string $key):string|bool
    {
        $headers = getallheaders();
        if(array_key_exists($key, $headers))
            return getallheaders()[$key];
        return false;
    }

    function clearRequestUrl():string
    {
        $url = $_SERVER["REDIRECT_URL"];
        if(str_ends_with($url, "/"))
            return substr($url, 0, strlen($url) - 1);
        return $url;
    }

    function callApi(string $url, string $method, array $headers = [], string $body = ""):array
    {
        $ch = curl_init("http://127.0.0.1/m-rpg/api/" . $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $result = json_decode(substr($response , $headerSize));
        $headers = headersToArray(substr($response, 0, $headerSize));
        return [$result, $code, $headers];
    }

    function headersToArray(string $input):array
    {
        $headers = array();
        $part = explode("\r\n", $input);
        for ($index = 0; $index < sizeof($part); $index++)
        {
            if (strlen($part[$index]) > 0 && strpos( $part[$index], ":"))
            {
                $headerName = substr($part[$index], 0, strpos($part[$index], ":"));
                $headerValue = substr($part[$index], strpos($part[$index], ":") + 1);
                $headers[$headerName] = $headerValue;
            }
        }
        return $headers;
    }

    function validPassword(string $password):bool|string
    {
        $password = trim($password);
        if(strlen($password) < 6)
            return "Password must be at least 6 characters long";
        if(!preg_match("@[A-Z]@", $password))
            return "Password must contain at least one large character";
        if(!preg_match("@[a-z]@", $password))
            return "Password must contain at least one small character";
        if(!preg_match("@[0-9]@", $password))
            return "Password must contain at least one number";
        if(!preg_match("@[^\w]@", $password))
            return "Password must contain at least one special character";
        return true;
    }

    function validUsername(string $username):bool|string
    {
        $username = trim($username);
        if(strlen($username) < 3)
            return "Username is to short";
        if(strlen($username) > 16)
            return "Username is to long";
        if(preg_match("[^a-zA-Z0-9_]", $username))
            return "Username can only contain letters, numbers and underscore";
        return true;
    }

    function validEmail(string $email):bool|string
    {
        $email = trim($email);
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
            return "Incorect email";
        return true;
    }

    function levelExp(int $level):int
    {
        if($level == 0)
            return 0;
        return (int)(log($level + 1) * 3750) + levelExp($level - 1);
    }
?>