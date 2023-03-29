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

use DiniTheorie\Instructor\Utils\RequestValidatorExtensions;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;

class RequestValidator
{
    public static function validateSectionId(Request $request, string $sectionId): void
    {
        if (!preg_match('/^\w+/i', $sectionId)) {
            throw new HttpBadRequestException($request, 'section name invalid; use only alphanummeric or underscore');
        }
    }

    public static function validateNewSectionId(Request $request, Storage $storage, string $chapterId, string $sectionId): void
    {
        self::validateSectionId($request, $sectionId);

        $sections = $storage->getSectionIds($chapterId);
        if (in_array($sectionId, $sections, true)) {
            throw new HttpBadRequestException($request, 'section already exists');
        }
    }

    public static function validateExistingSectionId(Request $request, Storage $storage, string $chapterId, string $sectionId): void
    {
        $sections = $storage->getSectionIds($chapterId);
        if (!in_array($sectionId, $sections, true)) {
            throw new HttpNotFoundException($request, 'section not found');
        }
    }

    public static function validateSection(Request $request, array $section): void
    {
        $configKeys = ['view'];
        RequestValidatorExtensions::checkExactlyKeysSet($request, $section['config'], $configKeys);
        if (!in_array($section['config']['view'], ['list', 'gallery'], true)) {
            throw new HttpBadRequestException($request, 'view mode not supported');
        }

        foreach ($section['translations'] as $translation) {
            RequestValidatorExtensions::checkExactlyKeysSet($request, $translation, ['language', 'title', 'description']);
            RequestValidatorExtensions::checkLanguageSupported($request, $translation['language']);
        }
    }
}
