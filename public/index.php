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
use DiniTheorie\Instructor\Utils\ManifestParser;
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

$manifestParser = new ManifestParser();
// frontend requests
if (str_starts_with($_SERVER['REQUEST_URI'], '/assets')) {
    $assetUrl = $manifestParser->getAssetUrl($_SERVER['REQUEST_URI']);
    header('Location: '.$assetUrl);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vite App</title>

    <?php echo $manifestParser->renderHtmlTags('main.js'); ?>
</head>

<body>
<div class="vue-app">
    <div id="app"></div>
</div>

</body>

</html>

