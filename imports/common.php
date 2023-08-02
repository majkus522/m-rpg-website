<?php
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
        if(preg_match("@[^a-zA-Z0-9_]@", $username))
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

    function connectToDatabase(string $query, array $parameters = []):array
    {
        require "databaseConfig.php";
        $mysqli = new mysqli($host, $user, $password, $database);
        $stmt = $mysqli->prepare($query);
        if(!empty($parameters))
        {
            $types = $parameters[0];
            unset($parameters[0]);
            $stmt->bind_param($types, ...$parameters);
        }
        $stmt->execute();
        $result = [];
        $stmtResult = $stmt->get_result();
        if($stmtResult === false)
            return [];
		while($row = $stmtResult->fetch_assoc())
			array_push($result, (object)$row);
        return $result;
    }

    function slugify(string $text):string
	{
		$divider = '-';
		$text = preg_replace('~[^\pL\d]+~u', $divider, $text);
		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
		$text = preg_replace('~[^-\w]+~', '', $text);
		$text = trim($text, $divider);
		$text = preg_replace('~-+~', $divider, $text);
		$text = strtolower($text);
		if (empty($text))
			return 'n-a';
		return $text;
	}

    function exitApi(int $code, string $message):void
    {
        http_response_code($code);
        exit($message);
    }

    function getHeader(string $key):string|bool
    {
        $headers = getallheaders();
        if(array_key_exists($key, $headers))
            return getallheaders()[$key];
        return false;
    }
?>