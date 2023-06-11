<?php
	function connectToDatabase(string $query):array
	{
		$host = "host";
		$user = "user";
		$password = "password";
		$database = "database";
		$connection = mysqli_connect($host, $user, $password, $database);
		mysqli_set_charset($connection, "utf8");
		$queryResult = mysqli_query($connection, $query);
		mysqli_close($connection);
		$result = [];
		while($row = mysqli_fetch_assoc($queryResult))
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