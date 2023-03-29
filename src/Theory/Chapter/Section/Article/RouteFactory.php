<?php

/*
 * This file is part of the DiniTheorie project.
 *
 * (c) Florian Moser <git@famoser.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DiniTheorie\Instructor\Theory\Chapter\Section\Article;

use DiniTheorie\Instructor\Theory\Chapter\RequestValidator as ChapterRequestValidator;
use DiniTheorie\Instructor\Theory\Chapter\Section\RequestValidator as SectionRequestValidator;
use DiniTheorie\Instructor\Theory\Chapter\Section\Storage as SectionStorage;
use DiniTheorie\Instructor\Theory\Chapter\Storage as ChapterStorage;
use DiniTheorie\Instructor\Utils\SlimExtensions;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;

class RouteFactory
{
    public static function addRoutes(RouteCollectorProxy $route, ChapterStorage $chapterStorage, SectionStorage $sectionStorage): void
    {
        $storage = new Storage();

        $route->group('/theory/chapter/{chapterId}/section/{sectionId}', function (RouteCollectorProxy $route) use ($chapterStorage, $sectionStorage, $storage) {
            $validateBasePath = function (Request $request, array $args) use ($chapterStorage, $sectionStorage) {
                $chapterId = $args['chapterId'];
                ChapterRequestValidator::validateExistingChapterId($request, $chapterStorage, $chapterId);

                $sectionId = $args['sectionId'];
                SectionRequestValidator::validateExistingSectionId($request, $sectionStorage, $chapterId, $sectionId);

                return [$chapterId, $sectionId];
            };

            $route->get('/articleIds', function (Request $request, Response $response, array $args) use ($validateBasePath, $storage) {
                [$chapterId, $sectionId] = $validateBasePath($request, $args);

                $categories = $storage->getArticleIds($chapterId, $sectionId);

                return SlimExtensions::createJsonResponse($response, $categories);
            });

            $route->get('/article/{id}', function (Request $request, Response $response, array $args) use ($validateBasePath, $storage) {
                [$chapterId, $sectionId] = $validateBasePath($request, $args);

                $articleId = $args['id'];
                RequestValidator::validateExistingArticleId($request, $storage, $chapterId, $sectionId, $articleId);

                $article = $storage->getArticle($chapterId, $sectionId, $articleId);

                return SlimExtensions::createJsonResponse($response, $article);
            });

            $route->post('/article', function (Request $request, Response $response, array $args) use ($validateBasePath, $storage) {
                [$chapterId, $sectionId] = $validateBasePath($request, $args);

                $article = SlimExtensions::parseJsonRequestBody($request);
                RequestValidator::validateNewArticleId($request, $storage, $chapterId, $sectionId, $article['id']);
                RequestValidator::validateArticle($request, $article);

                $storage->addArticle($chapterId, $sectionId, $article);

                $article = $storage->getArticle($chapterId, $sectionId, $article['id']);

                return SlimExtensions::createJsonResponse($response, $article, SlimExtensions::STATUS_CREATED);
            });

            $route->put('/article/{id}', function (Request $request, Response $response, array $args) use ($validateBasePath, $storage) {
                [$chapterId, $sectionId] = $validateBasePath($request, $args);

                $article = SlimExtensions::parseJsonRequestBody($request);
                RequestValidator::validateExistingArticleId($request, $storage, $chapterId, $sectionId, $args['id']);
                RequestValidator::validateArticle($request, $article);

                $storage->storeArticle($chapterId, $sectionId, $article);

                $article = $storage->getArticle($chapterId, $sectionId, $article['id']);

                return SlimExtensions::createJsonResponse($response, $article);
            });

            $route->delete('/article/{id}', function (Request $request, Response $response, array $args) use ($validateBasePath, $storage) {
                [$chapterId, $sectionId] = $validateBasePath($request, $args);

                $articleId = $args['id'];
                RequestValidator::validateExistingArticleId($request, $storage, $chapterId, $sectionId, $articleId);

                $storage->removeArticle($chapterId, $sectionId, $articleId);

                return $response->withStatus(SlimExtensions::STATUS_OK);
            });

            $route->get('/article/{id}/image/{filename}', function (Request $request, Response $response, array $args) use ($validateBasePath, $storage) {
                [$chapterId, $sectionId] = $validateBasePath($request, $args);

                $articleId = $args['id'];
                RequestValidator::validateArticleId($request, $articleId);

                $filename = $args['filename'];
                RequestValidator::validateExistingArticleImage($request, $storage, $chapterId, $sectionId, $articleId, $filename);

                $path = $storage->getArticleImagePath($chapterId, $sectionId, $articleId, $filename);

                return SlimExtensions::createFileResponse($response, $path, $filename);
            });

            $route->post('/article/{id}/image', function (Request $request, Response $response, array $args) use ($validateBasePath, $storage) {
                [$chapterId, $sectionId] = $validateBasePath($request, $args);

                $articleId = $args['id'];
                RequestValidator::validateArticleId($request, $articleId);

                $file = current($request->getUploadedFiles());
                RequestValidator::validateArticleImage($request, $file);
                $storage->replaceArticleImage($chapterId, $sectionId, $articleId, $file);

                return $response->withStatus(SlimExtensions::STATUS_OK);
            });

            $route->delete('/article/{id}/image/{filename}', function (Request $request, Response $response, array $args) use ($validateBasePath, $storage) {
                [$chapterId, $sectionId] = $validateBasePath($request, $args);

                $articleId = $args['id'];
                RequestValidator::validateArticleId($request, $articleId);

                $filename = $args['filename'];
                RequestValidator::validateExistingArticleImage($request, $storage, $chapterId, $sectionId, $articleId, $filename);

                $storage->removeArticleImage($chapterId, $sectionId, $articleId, $filename);

                return $response->withStatus(SlimExtensions::STATUS_OK);
            });
        });
    }
}
