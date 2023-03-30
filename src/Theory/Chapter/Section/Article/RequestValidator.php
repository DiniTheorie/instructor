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

use DiniTheorie\Instructor\Utils\RequestValidatorExtensions;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\UploadedFile;

class RequestValidator
{
    public static function validateArticleId(Request $request, string $articleId): void
    {
        if (!preg_match('/^\w+/i', $articleId)) {
            throw new HttpBadRequestException($request, 'article name invalid; use only alphanummeric or underscore');
        }
    }

    public static function validateNewArticleId(Request $request, Storage $storage, string $chapterId, string $sectionId, string $articleId): void
    {
        self::validateArticleId($request, $articleId);

        $articles = $storage->getArticleIds($chapterId, $sectionId);
        if (in_array($articleId, $articles, true)) {
            throw new HttpBadRequestException($request, 'article already exists');
        }
    }

    public static function validateExistingArticleId(Request $request, Storage $storage, string $chapterId, string $sectionId, string $articleId): void
    {
        $articles = $storage->getArticleIds($chapterId, $sectionId);
        if (!in_array($articleId, $articles, true)) {
            throw new HttpNotFoundException($request, 'article not found');
        }
    }

    public static function validateExistingArticleImage(Request $request, Storage $storage, string $chapterId, string $sectionId, string $articleId, string $filename): void
    {
        if (!$storage->checkArticleImageExists($chapterId, $sectionId, $articleId, $filename)) {
            throw new HttpNotFoundException($request, 'Image not found');
        }
    }

    public static function validateArticle(Request $request, array $article): void
    {
        $metaKeys = ['exam', 'important', 'text_asa', 'image_asa', 'answer_1_correct', 'answer_2_correct', 'answer_3_correct'];
        RequestValidatorExtensions::checkExactlyKeysSet($request, $article['meta'], $metaKeys);
        RequestValidatorExtensions::checkKeysBoolean($request, $article['meta'], $metaKeys);

        foreach ($article['translations'] as $translation) {
            RequestValidatorExtensions::checkExactlyKeysSet($request, $translation, ['language', 'article', 'answer_1', 'answer_2', 'answer_3'], ['explanation']);
            RequestValidatorExtensions::checkLanguageSupported($request, $translation['language']);
        }
    }

    public static function validateArticleImage(Request $request, ?UploadedFile $file): void
    {
        RequestValidatorExtensions::checkFileUploadSuccessful($request, $file);
        RequestValidatorExtensions::checkSupportedImageFile($request, $file);
    }
}
