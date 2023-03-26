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
use DiniTheorie\Instructor\Utils\HtmlEmitter;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

require __DIR__.'/../vendor/autoload.php';
if (false !== stripos($_SERVER['REQUEST_URI'], 'api')) {
    $app = AppFactory::create();
    $app->addErrorMiddleware(true, true, true);
    $repository = new Repository();
    $app->group('/api', function (RouteCollectorProxy $route) use ($repository) {
        RouteFactory::addRoutes($route, $repository);
    });
    $app->run();
} else {
    $htmlEmitter = new HtmlEmitter();
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Vite App</title>

        <?php echo $htmlEmitter->vite('main.js'); ?>

    </head>

    <body>
    <div class="vue-app">
        <div id="app"></div>
    </div>

    </body>

    </html>
<?php }
