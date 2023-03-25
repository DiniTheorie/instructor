<?php

/*
 * This file is part of the DiniTheorie project.
 *
 * (c) Florian Moser <git@famoser.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__.'/../vendor/autoload.php';

$app = AppFactory::create();
$examRepository = new ExamCategoryRepository(new GitDatabase());

$app->get('/exam/categories', function (Request $request, Response $response, array $args) use ($examRepository) {
    $categories = $examRepository->getCategories();

    return SlimExtensions::createJsonResponse($response, $categories);
});

$app->get('/exam/category/{id}', function (Request $request, Response $response, array $args) use ($examRepository) {
    $categoryId = $args['id'];
    RequestValidator::validateCategoryId($request, $examRepository, $categoryId);

    $category = $examRepository->getCategory($categoryId);

    return SlimExtensions::createJsonResponse($response, $category);
});

$app->post('/exam/category', function (Request $request, Response $response, array $args) use ($examRepository) {
    $category = SlimExtensions::parseJsonRequestBody($request);
    RequestValidator::validateCategory($request, $category);
    RequestValidator::validateNewCategoryId($request, $examRepository, $category['id']);

    $examRepository->addCategory($category);

    $category = $examRepository->getCategory($category['id']);

    return SlimExtensions::createJsonResponse($response, $category, SlimExtensions::STATUS_CREATED);
});

$app->put('/exam/category/{id}', function (Request $request, Response $response, array $args) use ($examRepository) {
    $categoryId = $args['id'];
    $category = SlimExtensions::parseJsonRequestBody($request);
    RequestValidator::validateCategoryId($request, $examRepository, $categoryId);
    RequestValidator::validateCategory($request, $category);

    $examRepository->storeCategory($categoryId, $category);

    $category = $examRepository->getCategory($category['id']);

    return SlimExtensions::createJsonResponse($response, $category);
});

$app->delete('/exam/category/{id}', function (Request $request, Response $response, array $args) use ($examRepository) {
    $categoryId = $args['id'];
    RequestValidator::validateCategoryId($request, $examRepository, $categoryId);

    $examRepository->removeCategory($categoryId);

    return $response->withStatus(SlimExtensions::STATUS_OK);
});

$app->run();
