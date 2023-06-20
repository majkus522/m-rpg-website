<?php
    class ApiResult
    {
        public $code;
        public $data;

        function __construct(string $data, int $code)
        {
            $this->code = $code;
            $this->data = $data;
        }
    }

    class User
    {
        public $id;
        public $username;

        function __construct(int $id, string $username)
        {
            $this->id = $id;
            $this->username = $username;
        }
    }

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

    function validPassword($password):bool|string
    {
        return true;
    }
?>