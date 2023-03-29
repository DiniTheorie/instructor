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

use DiniTheorie\Instructor\Utils\StorageExtensions;

class Storage
{
    private const TRANSLATION_FILE_PREFIX = 'introduction.';
    private const CONFIG_FILE_NAME = 'config.yml';

    private static function getSectionsDir(string $chapterId): string
    {
        return \DiniTheorie\Instructor\Theory\Chapter\Storage::CHAPTERS_DIR.'/'.$chapterId;
    }

    public static function getSectionDir(string $chapterId, string $id): string
    {
        return self::getSectionsDir($chapterId).'Storage.php/'.$id;
    }

    public function getSectionIds(string $chapterId): array
    {
        $sectionsDir = self::getSectionsDir($chapterId);

        return StorageExtensions::listDirectories($sectionsDir);
    }

    public function getSection(string $chapterId, string $id): array
    {
        $sectionDir = self::getSectionDir($chapterId, $id);
        $config = StorageExtensions::readYmlFile($sectionDir.'/'.self::CONFIG_FILE_NAME);
        $translations = StorageExtensions::readYmlTranslations($sectionDir, self::TRANSLATION_FILE_PREFIX);

        return ['id' => $id, 'config' => $config, 'translations' => $translations];
    }

    public function addSection(string $chapterId, array $chapter): void
    {
        $sectionDir = self::getSectionDir($chapterId, $chapter['id']);
        mkdir($sectionDir);

        self::storeSection($chapterId, $chapter);
    }

    public function storeSection(string $chapterId, array $chapter): void
    {
        $sectionDir = self::getSectionDir($chapterId, $chapter['id']);

        StorageExtensions::writeYmlFile($sectionDir.'/'.self::CONFIG_FILE_NAME, $chapter['config']);
        StorageExtensions::storeYmlTranslations($sectionDir, $chapter['translations'], self::TRANSLATION_FILE_PREFIX);
    }

    public function removeSection(string $chapterId, string $id): void
    {
        $sectionDir = self::getSectionDir($chapterId, $id);

        StorageExtensions::removeDirectoryRecursively($sectionDir);
    }
}
