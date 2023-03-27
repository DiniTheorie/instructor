<?php

/*
 * This file is part of the DiniTheorie project.
 *
 * (c) Florian Moser <git@famoser.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DiniTheorie\Instructor\ExamCategory;

use DiniTheorie\Instructor\Repository;
use DiniTheorie\Instructor\Utils\SlimExtensions;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;

class RouteFactory
{
    public static function addRoutes(RouteCollectorProxy $route, Repository $repository): void
    {
        $storage = new Storage($repository);

        $route->group('/exam', function (RouteCollectorProxy $route) use ($storage) {
            $route->get('/categoryIds', function (Request $request, Response $response, array $args) use ($storage) {
                $categories = $storage->getCategories();

                return SlimExtensions::createJsonResponse($response, $categories);
            });

            $route->get('/category/{id}', function (Request $request, Response $response, array $args) use ($storage) {
                $categoryId = $args['id'];
                RequestValidator::validateCategoryId($request, $storage, $categoryId);

                $category = $storage->getCategory($categoryId);

                return SlimExtensions::createJsonResponse($response, $category);
            });

            $route->post('/category', function (Request $request, Response $response, array $args) use ($storage) {
                $category = SlimExtensions::parseJsonRequestBody($request);
                RequestValidator::validateCategory($request, $category);
                RequestValidator::validateNewCategoryId($request, $storage, $category['id']);

                $storage->addCategory($category);

                $category = $storage->getCategory($category['id']);

                return SlimExtensions::createJsonResponse($response, $category, SlimExtensions::STATUS_CREATED);
            });

            $route->put('/category/{id}', function (Request $request, Response $response, array $args) use ($storage) {
                $categoryId = $args['id'];
                $category = SlimExtensions::parseJsonRequestBody($request);
                RequestValidator::validateCategoryId($request, $storage, $categoryId);
                RequestValidator::validateCategory($request, $category);

                $storage->storeCategory($categoryId, $category);

                $category = $storage->getCategory($category['id']);

                return SlimExtensions::createJsonResponse($response, $category);
            });

            $route->delete('/category/{id}', function (Request $request, Response $response, array $args) use ($storage) {
                $categoryId = $args['id'];
                RequestValidator::validateCategoryId($request, $storage, $categoryId);

                $storage->removeCategory($categoryId);

                return $response->withStatus(SlimExtensions::STATUS_OK);
            });
        });
    }
}
