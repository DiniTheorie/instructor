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

use DiniTheorie\Instructor\Theory\Chapter\Section\Storage as SectionStorage;
use DiniTheorie\Instructor\Utils\StorageExtensions;
use Slim\Psr7\UploadedFile;

class Storage
{
    private static function getArticlesDir(string $chapterId, string $sectionId): string
    {
        return SectionStorage::getSectionDir($chapterId, $sectionId);
    }

    private static function getArticleDir(string $chapterId, string $sectionId, string $id): string
    {
        return self::getArticlesDir($chapterId, $sectionId).'/'.$id;
    }

    public function getArticleIds(string $chapterId, string $sectionId): array
    {
        $dir = self::getArticlesDir($chapterId, $sectionId);

        return StorageExtensions::listDirectories($dir);
    }

    public function getArticle(string $chapterId, string $sectionId, string $id): array
    {
        $articleDir = self::getArticleDir($chapterId, $sectionId, $id);

        $translations = StorageExtensions::readMdTranslations($articleDir);
        $images = StorageExtensions::readSortedImages($articleDir);

        return ['id' => $id, 'translations' => $translations, 'imageUrls' => $images];
    }

    public function addArticle(string $chapterId, string $sectionId, array $article): void
    {
        $articleDir = self::getArticleDir($chapterId, $sectionId, $article['id']);
        mkdir($articleDir);

        self::storeArticle($chapterId, $sectionId, $article);
    }

    public function storeArticle(string $chapterId, string $sectionId, array $article): void
    {
        $articleDir = self::getArticleDir($chapterId, $sectionId, $article['id']);

        StorageExtensions::storeMdTranslations($articleDir, $article['translations']);
    }

    public function removeArticle(string $chapterId, string $sectionId, string $id): void
    {
        $articleDir = self::getArticleDir($chapterId, $sectionId, $id);

        StorageExtensions::removeDirectoryRecursively($articleDir);
    }

    public function checkArticleImageExists(string $chapterId, string $sectionId, string $id, string $filename): bool
    {
        $articleDir = self::getArticleDir($chapterId, $sectionId, $id);
        $images = StorageExtensions::readSortedImages($articleDir);
        if (in_array($filename, $images, true)) {
            return true;
        }

        return false;
    }

    public function replaceArticleImage(string $chapterId, string $sectionId, string $id, ?UploadedFile $article): void
    {
        $articleDir = self::getArticleDir($chapterId, $sectionId, $id);
        StorageExtensions::writeUploadedImage($articleDir, $article);
    }

    public function getArticleImagePath(string $chapterId, string $sectionId, string $id, string $filename): string
    {
        $articleDir = self::getArticleDir($chapterId, $sectionId, $id);

        return $articleDir.'/'.$filename;
    }

    public function removeArticleImage(string $chapterId, string $sectionId, string $id, string $filename): void
    {
        $articleDir = self::getArticleDir($chapterId, $sectionId, $id);
        StorageExtensions::removeFile($articleDir.'/'.$filename);
    }
}
