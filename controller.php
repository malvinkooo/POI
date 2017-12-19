<?php
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;
require 'vendor/autoload.php';
require 'functions.php';

$app = new \Slim\App();

$app->get('/api/places', function(Request $request, Response $response){
	global $db;
	$response = $response->withHeader('Content-type', 'application/json');

	$stm = $db->prepare("SELECT * FROM point_of_interest");
	if($stm->execute()) {
		$data = $stm->fetchAll(PDO::FETCH_ASSOC);
		$response->getBody()->write(json_encode($data));
	} else {
		$response->getBody()->write(json_encode($stm->errorInfo()));
		$newResponse = $response->withStatus(400);
		return $newResponse;
	}
});

$app->post('/api/places', function(Request $request, Response $response){
	global $db;
	$response = $response->withHeader('Content-type', 'application/json');

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
			$response->getBody()->write(json_encode($data));
		} else {
			$response->getBody()->write(json_encode($stm->errorInfo()));
			$newResponse = $response->withStatus(400);
			return $newResponse;
		}
	} else {
		$response->getBody()->write(json_encode($stm->errorInfo()));
		$newResponse = $response->withStatus(400);
		return $newResponse;
	}
});

$app->delete('/api/places/{id}', function(Request $request, Response $response, $args){
	global $db;
	$response = $response->withHeader('Content-type', 'application/json');

	$stm = $db->prepare("DELETE FROM `point_of_interest` WHERE id = ?");

	$id = $args['id'];
	if($stm->execute(array($id))) {
		$response->withStatus(200);
	} else {
		$response->getBody()->write(json_encode($stm->errorInfo()));
		$newResponse = $response->withStatus(400);
		return $newResponse;
	}
});

$app->put('/api/places/{id}', function(Request $request, Response $response){
});

$app->run();
?>