<?php

try {
	$db = new PDO('mysql:host=localhost;dbname=point_of_interest', 'root', '', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET CHARSET utf8'));
} catch(PDOException $e) {
	http_response_code(500);
	header('Content-Type', 'application/json');
	$erroObject = array(
		'error' => 'Не удалось подключиться к БД',
		'details' => $e->getMessage()
	);
	echo json_encode($erroObject);
	exit();
}
?>