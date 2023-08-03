<?php
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
?>