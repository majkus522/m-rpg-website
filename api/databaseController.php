<?php
    $mysql = null;

    function connectToDatabase(string $query, string $types = "", array $parameters = [], &$insertId = 0):array
    {
        global $mysql;
        $stmt = $mysql->prepare($query);
        if((!empty($parameters)) && strlen($types) > 0)
            $stmt->bind_param($types, ...$parameters);
        $stmt->execute();
        $result = [];
        $stmtResult = $stmt->get_result();
        if(str_starts_with($query, "insert"))
            $insertId = $stmt->insert_id;
        if($stmtResult === false)
            return [];
		while($row = $stmtResult->fetch_assoc())
			array_push($result, (object)$row);
        $stmt->close();
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

    function databaseOpen()
    {
        global $mysql;
        require "databaseConfig.php";
        $mysql = new mysqli($host, $user, $password, $database);
    }

    function databaseClose()
    {
        global $mysql;
        if($mysql != null)
            $mysql->close();
    }
?>