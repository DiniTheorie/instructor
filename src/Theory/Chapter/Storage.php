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

use DiniTheorie\Instructor\Repository;
use DiniTheorie\Instructor\Utils\StorageExtensions;

class Storage
{
    public const CHAPTERS_DIR = Repository::REPO_DIR.'/template/theory';
    private const TRANSLATION_FILE_PREFIX = 'introduction.';

    private static function getChapterDir(string $id): string
    {
        return self::CHAPTERS_DIR.'/'.$id;
    }

    public function getChapterIds(): array
    {
        return StorageExtensions::listDirectories(self::CHAPTERS_DIR);
    }

    public function getChapter(string $id): array
    {
        $chapterDir = self::getChapterDir($id);
        $translations = StorageExtensions::readTranslations($chapterDir, self::TRANSLATION_FILE_PREFIX);

        return ['id' => $id, 'translations' => $translations];
    }

    public function addChapter(array $chapter): void
    {
        $chapterDir = self::getChapterDir($chapter['id']);
        mkdir($chapterDir);

        self::storeChapter($chapter);
    }

    public function storeChapter(array $chapter): void
    {
        $chapterDir = self::getChapterDir($chapter['id']);

        StorageExtensions::storeTranslations($chapterDir, $chapter['translations'], self::TRANSLATION_FILE_PREFIX);
    }

    public function removeChapter(string $id): void
    {
        $chapterDir = self::getChapterDir($id);

        StorageExtensions::removeDirectoryRecursively($chapterDir);
    }
}
