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
 * Validate that the required params are set in the request
 */
$app->before(function(Request $req) use ($app, $logger) {
    if (!$req->query->has('length')) {
        $app->abort(400, "Missing length query param");
    }

    $length = intval($req->query->get('length'));
    if (!$length){
        $app->abort(400, "Length query param is not an integer");
    }

    if (!$req->query->has('pattern')){
        $app->abort(400, "Missing pattern query param");
    }

    if (strlen($req->query->get('pattern')) == 0){
        $app->abort(400, "Empty pattern query param");
    }
});

/**
 * pattern=regexp
 * length=length of words to find
 * allow pattern to be optional and respond with all words with this length?
 */
$app->get("/words", function(Request $req) use ($app, $dict, $logger){
    $result = $dict->find($req->query->get('pattern'), $req->query->get('length'));
    return $app->json($result);
});

$app->error(function (Exception $e) use($logger) {
    $logger->addError($e->getMessage());
    return new Response($e->getMessage());
});

return $app;
