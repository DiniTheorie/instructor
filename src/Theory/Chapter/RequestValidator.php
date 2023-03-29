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

use DiniTheorie\Instructor\Utils\RequestValidatorExtensions;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;

class RequestValidator
{
    public static function validateChapterId(Request $request, string $chapterId): void
    {
        if (!preg_match('/^\w+/i', $chapterId)) {
            throw new HttpBadRequestException($request, 'chapter name invalid; use only alphanummeric or underscore');
        }
    }

    public static function validateNewChapterId(Request $request, Storage $storage, string $chapterId): void
    {
        self::validateChapterId($request, $chapterId);

        $chapters = $storage->getChapterIds();
        if (in_array($chapterId, $chapters, true)) {
            throw new HttpBadRequestException($request, 'chapter already exists');
        }
    }

    public static function validateExistingChapterId(Request $request, Storage $storage, string $chapterId): void
    {
        $chapters = $storage->getChapterIds();
        if (!in_array($chapterId, $chapters, true)) {
            throw new HttpNotFoundException($request, 'chapter not found');
        }
    }

    public static function validateChapter(Request $request, array $chapter): void
    {
        foreach ($chapter['translations'] as $translation) {
            RequestValidatorExtensions::checkExactlyKeysSet($request, $translation, ['language', 'title', 'description']);
            RequestValidatorExtensions::checkLanguageSupported($request, $translation['language']);
        }
    }
}
