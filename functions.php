<?php

try {
	$db = new PDO('mysql:host=localhost;dbname=point_of_interest', 'root', '', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARSET utf8"));
} catch(PDOException $e) {
	$error = array();
	$error['Result'] = 'ERROR';
	$error['Message'] = "Ошибка: ".$e -> getMessage();
	echo json_encode($error);
	exit();
}
?>