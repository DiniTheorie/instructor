<?php

/*
 * This file is part of the DiniTheorie project.
 *
 * (c) Florian Moser <git@famoser.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use DiniTheorie\Instructor\Repository;
use DiniTheorie\Instructor\utils\SlimExtensions;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__.'/../vendor/autoload.php';

$app = AppFactory::create();
$repository = new Repository();

$app->get('/', function (Request $request, Response $response, array $args) {
    return SlimExtensions::createHTMLResponse($response, 'index.html');
});

// RouteFactory::addRoutes($app, $repository);

$app->run();
