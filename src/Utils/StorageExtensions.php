<?php

/*
 * This file is part of the DiniTheorie project.
 *
 * (c) Florian Moser <git@famoser.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DiniTheorie\Instructor\Utils;

use Slim\Psr7\UploadedFile;
use Symfony\Component\Yaml\Yaml;

class StorageExtensions
{
    public static function removeDirectoryRecursively(string $dir): void
    {
        if (!file_exists($dir)) {
            return;
        }

        if (!is_dir($dir)) {
            unlink($dir);

            return;
        }

        foreach (scandir($dir) as $item) {
            if ('.' === $item || '..' === $item) {
                continue;
            }

            self::removeDirectoryRecursively($dir.DIRECTORY_SEPARATOR.$item);
        }

        rmdir($dir);
    }

    public static function listDirectories(string $dir): array
    {
        $nodes = scandir($dir);

        $folders = [];
        foreach ($nodes as $node) {
            $path = $dir.'/'.$node;
            if (!is_dir($path) || in_array($node, ['.', '..'], true)) {
                continue;
            }

            $folders[] = $node;
        }

        return $folders;
    }

    public static function storeTranslations(string $dir, array $translations, string $filePrefix = ''): void
    {
        // remove old translations
        $nodes = glob($dir.'/'.$filePrefix.'??.yml');
        foreach ($nodes as $node) {
            unlink($node);
        }

        // write new translations
        foreach ($translations as $translation) {
            $language = $translation['language'];
            unset($translation['language']);

            $filePath = $dir.'/'.$filePrefix.$language.'.yml';
            self::writeYmlFile($filePath, $translation);
        }
    }

    public static function readTranslations(string $categoryDir, string $filePrefix = ''): array
    {
        $nodes = glob($categoryDir.'/'.$filePrefix.'??.yml');

        $translations = [];
        foreach ($nodes as $node) {
            if (!is_file($node)) {
                continue;
            }

            $parsedContent = self::readYmlFile($node);

            $fileSuffix = substr($node, -6); // get de.yml
            $parsedContent['language'] = substr($fileSuffix, 0, 2); // read out de

            $translations[] = $parsedContent;
        }

        return $translations;
    }

    public static function readYmlFile(string $filePath): array
    {
        $content = file_get_contents($filePath);

        return Yaml::parse($content);
    }

    public static function writeYmlFile(string $filePath, array $content): void
    {
        $content = Yaml::dump($content);
        file_put_contents($filePath, $content);
    }

    public static function writeUploadedFile(string $dir, UploadedFile $file): void
    {
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $filename = pathinfo($file->getClientFilename(), PATHINFO_BASENAME);
        $file->moveTo($dir.'/'.$filename);
    }
}
