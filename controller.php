<?php
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;
require 'vendor/autoload.php';

$app = new \Slim\App();

$app->get('/api/1', function(Request $request, Response $response){
	echo "Hello";
});
?>