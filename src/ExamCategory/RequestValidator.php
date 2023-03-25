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

use DiniTheorie\Instructor\utils\RequestValidatorExtensions;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;

class RequestValidator
{
    public static function validateNewCategoryId(Request $request, Storage $examRepository, string $categoryId): void
    {
        if (!preg_match('/^\w+/i', $categoryId)) {
            throw new HttpBadRequestException($request, 'category name invalid; use only alphanummeric or underscore');
        }

        $categories = $examRepository->getCategories();
        if (in_array($categoryId, $categories, true)) {
            throw new HttpBadRequestException($request, 'category already exists');
        }
    }

    public static function validateCategoryId(Request $request, Storage $examRepository, string $categoryId): void
    {
        $categories = $examRepository->getCategories();
        if (!in_array($categoryId, $categories, true)) {
            throw new HttpNotFoundException($request, 'category not found');
        }
    }

    public static function validateCategory(Request $request, array $category): void
    {
        foreach ($category['translations'] as $translation) {
            RequestValidatorExtensions::checkExactlyKeysSet($request, $translation, 'language', 'name', 'description');
            RequestValidatorExtensions::checkLanguageSupported($request, $translation['language']);
        }
    }
}
