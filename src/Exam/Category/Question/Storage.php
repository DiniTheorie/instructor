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

        $translations = StorageExtensions::readTranslations($questionDir);
        $meta = StorageExtensions::readYmlFile($questionDir.'/'.self::META_FILE_NAME);
        $examImage = StorageExtensions::readImage($questionDir, self::EXAM_FILE_NAME);
        $images = StorageExtensions::readFilteredImages($questionDir, self::EXAM_FILE_NAME);

        return ['id' => $id, 'meta' => $meta, 'translations' => $translations, 'exam_image' => $examImage, 'images' => $images];
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

        StorageExtensions::storeTranslations($questionDir, $question['translations']);
        StorageExtensions::writeYmlFile($questionDir.'/'.self::META_FILE_NAME, $question['meta']);
    }

    public function removeQuestion(string $categoryId, string $id): void
    {
        $questionDir = self::getQuestionDir($categoryId, $id);

        StorageExtensions::removeDirectoryRecursively($questionDir);
    }

    public function replaceQuestionImage(string $categoryId, string $id, ?UploadedFile $question): void
    {
        $questionDir = self::getQuestionDir($categoryId, $id);
        StorageExtensions::writeUploadedImage($questionDir, $question);
    }
}
