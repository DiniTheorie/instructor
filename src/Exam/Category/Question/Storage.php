<?php

/*
 * This file is part of the DiniTheorie project.
 *
 * (c) Florian Moser <git@famoser.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DiniTheorie\Instructor\Exam\Category\Question;

use DiniTheorie\Instructor\Utils\StorageExtensions;
use Slim\Psr7\UploadedFile;

class Storage
{
    private const META_FILE_NAME = 'meta.yml';
    private const EXAM_FILE_NAME = 'exam';

    private static function getQuestionsDir(string $categoryId): string
    {
        return \DiniTheorie\Instructor\Exam\Category\Storage::CATEGORIES_DIR.'/'.$categoryId;
    }

    private static function getQuestionDir(string $categoryId, string $id): string
    {
        return self::getQuestionsDir($categoryId).'/'.$id;
    }

    public function getQuestionIds(string $categoryId): array
    {
        $dir = self::getQuestionsDir($categoryId);

        return StorageExtensions::listDirectories($dir);
    }

    public function getQuestion(string $categoryId, string $id): array
    {
        $questionDir = self::getQuestionDir($categoryId, $id);

        $translations = StorageExtensions::readYmlTranslations($questionDir);
        $meta = StorageExtensions::readYmlFile($questionDir.'/'.self::META_FILE_NAME);
        $examImage = StorageExtensions::readImage($questionDir, self::EXAM_FILE_NAME);
        $images = StorageExtensions::readSortedImages($questionDir, self::EXAM_FILE_NAME);

        return ['id' => $id, 'meta' => $meta, 'translations' => $translations, 'examImageUrl' => $examImage, 'imageUrls' => $images];
    }

    public function addQuestion(string $categoryId, array $question): void
    {
        $questionDir = self::getQuestionDir($categoryId, $question['id']);
        mkdir($questionDir);

        self::storeQuestion($categoryId, $question);
    }

    public function storeQuestion(string $categoryId, array $question): void
    {
        $questionDir = self::getQuestionDir($categoryId, $question['id']);

        StorageExtensions::storeYmlTranslations($questionDir, $question['translations']);
        StorageExtensions::writeYmlFile($questionDir.'/'.self::META_FILE_NAME, $question['meta']);
    }

    public function removeQuestion(string $categoryId, string $id): void
    {
        $questionDir = self::getQuestionDir($categoryId, $id);

        StorageExtensions::removeDirectoryRecursively($questionDir);
    }

    public function checkQuestionImageExists(string $categoryId, string $id, string $filename): bool
    {
        $questionDir = self::getQuestionDir($categoryId, $id);
        $examImage = StorageExtensions::readImage($questionDir, self::EXAM_FILE_NAME);
        if ($examImage === $filename) {
            return true;
        }

        $images = StorageExtensions::readSortedImages($questionDir, self::EXAM_FILE_NAME);
        if (in_array($filename, $images, true)) {
            return true;
        }

        return false;
    }

    public function replaceQuestionImage(string $categoryId, string $id, ?UploadedFile $question): void
    {
        $questionDir = self::getQuestionDir($categoryId, $id);
        StorageExtensions::writeUploadedImage($questionDir, $question);
    }

    public function getQuestionImagePath(string $categoryId, string $id, string $filename): string
    {
        $questionDir = self::getQuestionDir($categoryId, $id);

        return $questionDir.'/'.$filename;
    }

    public function removeQuestionImage(string $categoryId, string $id, string $filename): void
    {
        $questionDir = self::getQuestionDir($categoryId, $id);
        StorageExtensions::removeFile($questionDir.'/'.$filename);
    }
}
