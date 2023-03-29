<?php

/*
 * This file is part of the DiniTheorie project.
 *
 * (c) Florian Moser <git@famoser.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DiniTheorie\Instructor\Theory\Chapter;

use DiniTheorie\Instructor\Utils\SlimExtensions;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;

class RouteFactory
{
    public static function addRoutes(RouteCollectorProxy $route): void
    {
        $storage = new Storage();

        $route->group('/theory', function (RouteCollectorProxy $route) use ($storage) {
            $route->get('/chapterIds', function (Request $request, Response $response, array $args) use ($storage) {
                $chapters = $storage->getChapterIds();

                return SlimExtensions::createJsonResponse($response, $chapters);
            });

            $route->get('/chapter/{id}', function (Request $request, Response $response, array $args) use ($storage) {
                $chapterId = $args['id'];
                RequestValidator::validateExistingChapterId($request, $storage, $chapterId);

                $chapter = $storage->getChapter($chapterId);

                return SlimExtensions::createJsonResponse($response, $chapter);
            });

            $route->post('/chapter', function (Request $request, Response $response, array $args) use ($storage) {
                $chapter = SlimExtensions::parseJsonRequestBody($request);
                RequestValidator::validateNewChapterId($request, $storage, $chapter['id']);
                RequestValidator::validateChapter($request, $chapter);

                $storage->addChapter($chapter);

                $chapter = $storage->getChapter($chapter['id']);

                return SlimExtensions::createJsonResponse($response, $chapter, SlimExtensions::STATUS_CREATED);
            });

            $route->put('/chapter/{id}', function (Request $request, Response $response, array $args) use ($storage) {
                $chapter = SlimExtensions::parseJsonRequestBody($request);
                RequestValidator::validateExistingChapterId($request, $storage, $args['id']);
                RequestValidator::validateChapter($request, $chapter);

                $storage->storeChapter($chapter);

                $chapter = $storage->getChapter($chapter['id']);

                return SlimExtensions::createJsonResponse($response, $chapter);
            });

            $route->delete('/chapter/{id}', function (Request $request, Response $response, array $args) use ($storage) {
                $chapterId = $args['id'];
                RequestValidator::validateExistingChapterId($request, $storage, $chapterId);

                $storage->removeChapter($chapterId);

                return $response->withStatus(SlimExtensions::STATUS_OK);
            });
        });
    }
}
