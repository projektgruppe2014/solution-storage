<?php

require __DIR__.'/../vendor/autoload.php';

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = new Application();
$app['db_dir'] = __DIR__.'/../db';

$app->get('/{token}', function (Application $app, $token) {
    if (!file_exists($app['db_dir'].'/'.$token)) {
        $app->abort(404, 'Solution for token '.$token.' not found');
    }

    $response = new Response(file_get_contents($app['db_dir'].'/'.$token));
    $response->headers->set('Content-Type', 'application/json');

    return $response;
});

$app->post('/', function (Application $app, Request $request) {
    $solution = json_encode(json_decode($request->getContent()));
    $token = md5($solution);
    file_put_contents($app['db_dir'].'/'.$token, $solution);

    $response = new Response($token);
    $response->headers->set('Content-Type', 'text/plain; charset=UTF-8');

    return $response;
});

$app->run();
