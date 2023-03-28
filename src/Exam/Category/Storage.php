<?php

/*
 * This file is part of the DiniTheorie project.
 *
 * (c) Florian Moser <git@famoser.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DiniTheorie\Instructor\Exam\Category;

use DiniTheorie\Instructor\Repository;
use DiniTheorie\Instructor\Utils\StorageExtensions;

class Storage
{
    public const CATEGORIES_DIR = Repository::REPO_DIR.'/template/questions';
    private const TRANSLATION_FILE_PREFIX = 'introduction.';

    private static function getCategoryDir(string $id): string
    {
        return self::CATEGORIES_DIR.'/'.$id;
    }

    public function getCategoryIds(): array
    {
        return StorageExtensions::listDirectories(self::CATEGORIES_DIR);
    }

    public function getCategory(string $id): array
    {
        $categoryDir = self::getCategoryDir($id);
        $translations = StorageExtensions::readTranslations($categoryDir, self::TRANSLATION_FILE_PREFIX);

        return ['id' => $id, 'translations' => $translations];
    }

    public function addCategory(array $category): void
    {
        $categoryDir = self::getCategoryDir($category['id']);
        mkdir($categoryDir);

        self::storeCategory($category);
    }

    public function storeCategory(array $category): void
    {
        $categoryDir = self::getCategoryDir($category['id']);

        StorageExtensions::storeTranslations($categoryDir, $category['translations'], self::TRANSLATION_FILE_PREFIX);
    }

    public function removeCategory(string $id): void
    {
        $categoryDir = self::getCategoryDir($id);

        StorageExtensions::removeDirectoryRecursively($categoryDir);
    }
}
