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

// This would be your framework default bootstrap file

// During dev, this file would be hit when accessing your local host, like:
// http://vite-php-setup.test

require_once __DIR__.'/helper.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vite App</title>

    <?php echo vite('main.js'); ?>

</head>

<body>
<div class="vue-app">
    <div id="app"></div>
</div>

</body>

</html>
