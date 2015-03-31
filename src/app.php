<?php

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Monolog\Logger;
use Monolog\Handler\ErrorLogHandler;

require_once __DIR__ . '/vendor/autoload.php';

$app = new Application();

$logger = new Logger('log');
$logger->pushHandler(new ErrorLogHandler());

/**
 * pattern=regexp
 * length=length of words to find
 */
$app->get("/words", function(Request $req) use ($app, $logger){

});

$app->error(function (Exception $e) use($logger) {
    $logger->addError($e->getMessage());
    return new Response($e->getMessage());
});

return $app;
