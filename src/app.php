<?php

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Monolog\Logger;
use Monolog\Handler\ErrorLogHandler;
use Ace\Dictionary\SortedDictionary;

require_once __DIR__ . '/vendor/autoload.php';

$app = new Application();

$logger = new Logger('log');
$logger->pushHandler(new ErrorLogHandler());

$dict_file = __DIR__.'/data/dict-3';
$dict = new SortedDictionary($dict_file);

/**
 * pattern=regexp
 * length=length of words to find
 */
$app->get("/words", function(Request $req) use ($app, $dict, $logger){
    $pattern = $req->query->get('pattern');
    $length = $req->query->get('length');
    $result = $dict->find($pattern, $length);
    $logger->addDebug(print_r($result,1));
    return $app->json($result);
});

$app->error(function (Exception $e) use($logger) {
    $logger->addError($e->getMessage());
    return new Response($e->getMessage());
});

return $app;
