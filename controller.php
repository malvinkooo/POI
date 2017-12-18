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
});

$app->delete('/api/points', function(Request $request, Response $response){
});

$app->put('/api/points', function(Request $request, Response $response){
});

$app->run();
?>