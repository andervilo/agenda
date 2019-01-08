<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;	
	
require 'vendor/autoload.php'; 

use \RedBeanPHP\R as R;


R::setup('sqlite:tests.db'); 


$app = new \Slim\App(); 

$app->post('/pessoas', function ($request, $response, $args) { 
	$pessoa=R::dispense("pessoa");
	$param = $request->getParsedBody();
	$pessoa->nome=$param["nome"];
	$pessoa->telefone = $param["telefone"];
	$id=R::store($pessoa);
	return $response->withJson($pessoa->export()); 
}); 

$app->get("/pessoas", function($req, $res, $args){
	$pessoas = R::findAll("pessoa");
	return	$res->withJson(R::exportAll($pessoas));
});


$app->get("/pessoas/{id}", function($req, $res, $args){
	$pessoa = R::load("pessoa", $args["id"]);
	return	$res->withJson($pessoa->export());
});

$app->run();
