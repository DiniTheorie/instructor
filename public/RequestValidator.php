<?php

use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Request;

class RequestValidator
{
    public static function validateNewCategoryId(Request $request, ExamCategoryRepository $examRepository, string $categoryId): void
    {
        if (!preg_match('/^\w+/i', $categoryId)) {
            throw new HttpBadRequestException($request, 'category name invalid; use only alphanummeric or underscore');
        }

        $categories = $examRepository->getCategories();
        if (in_array($categoryId, $categories, true)) {
            throw new HttpBadRequestException($request, 'category already exists');
        }
    }

    public static function validateCategoryId(Request $request, ExamCategoryRepository $examRepository, string $categoryId): void
    {
        $categories = $examRepository->getCategories();
        if (!in_array($categoryId, $categories, true)) {
            throw new HttpNotFoundException($request, 'category not found');
        }
    }

    public static function validateCategory(Request $request, array $category): void
    {
        foreach ($category['translations'] as $translation) {
            self::checkExactlyKeysSet($request, $translation, 'language', 'name', 'description');
            self::checkLanguageSupported($request, $translation['language']);
        }
    }

    private static function checkExactlyKeysSet(Request $request, array $array, ...$keys): void
    {
        if (count(array_keys($array)) !== count($keys)) {
            throw new HttpNotFoundException($request, 'you must provide exactly ' . count($keys) . ' keys.');
        }

        foreach ($keys as $key) {
            if (!key_exists($key, $array)) {
                throw new HttpNotFoundException($request, 'key ' . $key . ' expected, but not provided.');
            }
        }
    }

    private static function checkLanguageSupported(Request $request, string $language): void
    {
        if (!in_array($language, ['de', 'en', 'fr', 'it'])) {
            throw new HttpNotFoundException($request, 'language not supported');
        }
    }
}
