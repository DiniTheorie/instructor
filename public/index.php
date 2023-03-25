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

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();
$repository = new Repository();

RouteFactory::addRoutes($app, $repository);

$app->run();
