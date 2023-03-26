<?php

/*
 * This file is part of the DiniTheorie project.
 *
 * (c) Florian Moser <git@famoser.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use DiniTheorie\Instructor\ExamCategory\RouteFactory;
use DiniTheorie\Instructor\Repository;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Symfony\Component\Dotenv\Dotenv;

require __DIR__.'/../vendor/autoload.php';

(new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
if (str_starts_with($_SERVER['REQUEST_URI'], '/api')) {
    $app = AppFactory::create();
    $app->addRoutingMiddleware();
    $app->addErrorMiddleware('dev' === $_SERVER['APP_ENV'], true, true);

    $app->group('/api', function (RouteCollectorProxy $route) {
        $repository = new Repository();
        RouteFactory::addRoutes($route, $repository);
    });
    $app->run();
    exit;
}

// when developing locally, use the server provided by vite
include 'index.html';
