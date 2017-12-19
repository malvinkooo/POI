<?php
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;
require 'vendor/autoload.php';
require 'functions.php';

$app = new \Slim\App();

$app->get('/api/places', function(Request $request, Response $response){
	global $db;

	$stm = $db->prepare("SELECT * FROM `point_of_interest`");
	if($stm->execute()) {
		$data = $stm->fetchAll(PDO::FETCH_ASSOC);
		return $response->withJson($data);
	} else {
		$errorObject = array(
			'error' => 'Не удалось получить список маркеров в БД.',
			'details' => $stm->errorInfo()
		);
		return $response->withStatus(500)->withJson($errorObject);
	}
});

$app->post('/api/places', function(Request $request, Response $response){
	global $db;

	$stm = $db->prepare("INSERT INTO `point_of_interest` (`lat`, `lng`) VALUES (:lat, :lng)");

	$postParams = $request->getParsedBody();
	$params = array(
		':lat' => $postParams['lat'],
		':lng' => $postParams['lng'],
	);
	if($stm->execute($params)) {
		$currentId = $db->lastInsertId();

		$getLastMarker = $db->prepare("SELECT * FROM `point_of_interest` WHERE id = ?");

		if($getLastMarker->execute(array($currentId))) {
			$data = $getLastMarker->fetchAll(PDO::FETCH_ASSOC);
			return $response->withJson($data[0]);
		} else {
			$errorObject = array(
				'error' => 'Не удалось получиь созданный маркер из БД',
				'details' => $getLastMarker->errorInfo()
			);
			return $response->withStatus(500)->withJson($errorObject);
		}
	} else {
		$errorObject = array(
			'error' => 'Не удалось добавить новый маркер в БД',
			'details' => $stm->errorInfo()
		);
		return $response->withStatus(500)->withJson($errorObject);
	}
});

$app->delete('/api/places/{id}', function(Request $request, Response $response, $args){
	global $db;

	$stm = $db->prepare("DELETE FROM `point_of_interest` WHERE id = ?");

	$id = $args['id'];
	if($stm->execute(array($id))) {
		return $response->withStatus(200);
	} else {
		$errorObject = array(
			'error' => 'Не удалось удалить маркер из БД',
			'details' => $stm->errorInfo()
		);
		return $response->withStatus(500)->withJson($errorObject);
	}
});

$app->put('/api/places/{id}', function(Request $request, Response $response, $args){
	global $db;

	$stm = $db->prepare("UPDATE `point_of_interest` SET text = :text WHERE id = :id");

	$res = $stm->execute(array(
		':text' => $request->getParsedBody()['text'],
		':id' => $args['id']
	));
	if(!$res) {
		$errorObject = array(
			'error' => 'Не удалось отредактировать маркер в БД',
			'details' => $stm->errorInfo()
		);
		return $response->withStatus(500)->withJson($errorObject);
	}
});

$app->run();
?>