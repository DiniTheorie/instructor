<?php

/*
 * This file is part of the DiniTheorie project.
 *
 * (c) Florian Moser <git@famoser.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DiniTheorie\Instructor\Theory\Chapter\Section;

use DiniTheorie\Instructor\Theory\Chapter\RequestValidator as ChapterRequestValidator;
use DiniTheorie\Instructor\Theory\Chapter\Section\Article\RouteFactory as ArticleRouteFactory;
use DiniTheorie\Instructor\Theory\Chapter\Storage as ChapterStorage;
use DiniTheorie\Instructor\Utils\SlimExtensions;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;

class RouteFactory
{
    public static function addRoutes(RouteCollectorProxy $route, ChapterStorage $chapterStorage): void
    {
        $storage = new Storage();

        ArticleRouteFactory::addRoutes($route, $chapterStorage, $storage);

        $route->group('/theory/chapter/{chapterId}', function (RouteCollectorProxy $route) use ($chapterStorage, $storage) {
            $validateBasePath = function (Request $request, array $args) use ($chapterStorage) {
                $chapterId = $args['chapterId'];
                ChapterRequestValidator::validateExistingChapterId($request, $chapterStorage, $chapterId);

                return $chapterId;
            };

            $route->get('/sectionIds', function (Request $request, Response $response, array $args) use ($validateBasePath, $storage) {
                $chapterId = $validateBasePath($request, $args);

                $categories = $storage->getSectionIds($chapterId);

                return SlimExtensions::createJsonResponse($response, $categories);
            });

            $route->get('/section/{id}', function (Request $request, Response $response, array $args) use ($validateBasePath, $storage) {
                $chapterId = $validateBasePath($request, $args);

                $sectionId = $args['id'];
                RequestValidator::validateExistingSectionId($request, $storage, $chapterId, $sectionId);

                $section = $storage->getSection($chapterId, $sectionId);

                return SlimExtensions::createJsonResponse($response, $section);
            });

            $route->post('/section', function (Request $request, Response $response, array $args) use ($validateBasePath, $storage) {
                $chapterId = $validateBasePath($request, $args);

                $section = SlimExtensions::parseJsonRequestBody($request);
                RequestValidator::validateNewSectionId($request, $storage, $chapterId, $section['id']);
                RequestValidator::validateSection($request, $section);

                $storage->addSection($chapterId, $section);

                $section = $storage->getSection($chapterId, $section['id']);

                return SlimExtensions::createJsonResponse($response, $section, SlimExtensions::STATUS_CREATED);
            });

            $route->put('/section/{id}', function (Request $request, Response $response, array $args) use ($validateBasePath, $storage) {
                $chapterId = $validateBasePath($request, $args);

                $section = SlimExtensions::parseJsonRequestBody($request);
                RequestValidator::validateExistingSectionId($request, $storage, $chapterId, $args['id']);
                RequestValidator::validateSection($request, $section);

                $storage->storeSection($chapterId, $section);

                $section = $storage->getSection($chapterId, $section['id']);

                return SlimExtensions::createJsonResponse($response, $section);
            });

            $route->delete('/section/{id}', function (Request $request, Response $response, array $args) use ($validateBasePath, $storage) {
                $chapterId = $validateBasePath($request, $args);

                $sectionId = $args['id'];
                RequestValidator::validateExistingSectionId($request, $storage, $chapterId, $sectionId);

                $storage->removeSection($chapterId, $sectionId);

                return $response->withStatus(SlimExtensions::STATUS_OK);
            });
        });
    }
}
