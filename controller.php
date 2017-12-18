<?php
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;
require 'vendor/autoload.php';
require 'functions.php';

$app = new \Slim\App();

$app->get('/api/points', function(Request $request, Response $response){
	global $db;
	$response = $response->withHeader('Content-type', 'application/json');

	$stm = $db->prepare("SELECT * FROM point_of_interest");
	if($stm->execute()) {
		$data = $stm->fetchAll(PDO::FETCH_ASSOC);
		$response->getBody()->write(json_encode($data));
	}
});

$app->post('/api/points', function(Request $request, Response $response){
	global $db;
	$response = $response->withHeader('Content-type', 'application/json');

	$stm = $db->prepare("INSERT INTO `point_of_interest` (`lat`, `lng`) VALUES (:lat, :lng)");

	$postParams = $request->getParsedBody();
	$params = array(
		':lat' => $postParams['lat'],
		':lng' => $postParams['lng'],
	);
	if($stm->execute($params)) {
		$currentId = array();
		$currentId['id'] = $db->lastInsertId();
		$response->getBody()->write(json_encode($currentId));
	}

});

$app->delete('/api/points', function(Request $request, Response $response, $args){
	global $db;

	$stm = $db->prepare("DELETE FROM `point_of_interest` WHERE id = ?");
	$id = $request->getParams();
	$id = $id['id'];
	echo $id;
	$stm->execute(array($id));
});

$app->put('/api/points', function(Request $request, Response $response){
});

$app->run();
?>