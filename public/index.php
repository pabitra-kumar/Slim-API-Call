<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->get('/api/ticker=AAPL', function (Request $request, Response $response, $args) {
    $dsn = 'mysql:host=127.0.0.1;dbname=api_data;charset=utf8';
    $pdo = new PDO($dsn, 'root', null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $stmt = $pdo->query('SELECT * FROM sample_data where ticker = "AAPL"');
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $body = json_encode($data);
    $response->getBody()->write($body);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/api/ticker=AAPL&column=revenue,gp&period=5y', function (Request $request, Response $response, $args) {
    $dsn = 'mysql:host=127.0.0.1;dbname=api_data;charset=utf8';
    $pdo = new PDO($dsn, 'root', null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $stmt = $pdo->query('SELECT ticker,date,revenue,gp FROM sample_data where ticker = "AAPL" and TIMESTAMPDIFF(YEAR, date, CURDATE()) < 5 ;');
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $body = json_encode($data);
    $response->getBody()->write($body);
    return $response->withHeader('Content-Type', 'application/json');
});
$app->get('/api/ticker=ZS&column=revenue,gp&period=5y', function (Request $request, Response $response, $args) {
    $dsn = 'mysql:host=127.0.0.1;dbname=api_data;charset=utf8';
    $pdo = new PDO($dsn, 'root', null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $stmt = $pdo->query('SELECT ticker, date, revenue, gp
    FROM sample_data
    WHERE ticker = "ZS"
    AND date >= DATE_SUB(CURDATE(), INTERVAL 5 YEAR);');
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $body = json_encode($data);
    $response->getBody()->write($body);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();
