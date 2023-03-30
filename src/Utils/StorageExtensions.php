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

use DiniTheorie\Instructor\PathHelper;
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

    public static function storeYmlTranslations(string $dir, array $translations, string $filePrefix = ''): void
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

    public static function readYmlTranslations(string $categoryDir, string $filePrefix = ''): array
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

    public static function storeMdTranslations(string $dir, array $translations): void
    {
        // remove old translations
        $nodes = glob($dir.'/??.md');
        foreach ($nodes as $node) {
            unlink($node);
        }

        // write new translations
        foreach ($translations as $translation) {
            $language = $translation['language'];
            unset($translation['language']);

            $filePath = $dir.'/'.$language.'.md';
            file_put_contents($filePath, $translation['markdown']);
        }
    }

    public static function readMdTranslations(string $dir): array
    {
        $nodes = glob($dir.'/??.md');

        $translations = [];
        foreach ($nodes as $node) {
            if (!is_file($node)) {
                continue;
            }

            $markdown = file_get_contents($node);

            $fileSuffix = substr($node, -5); // get de.md
            $language = substr($fileSuffix, 0, 2); // read out de
            $translation = ['language' => $language, 'markdown' => $markdown];

            $translations[] = $translation;
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

    public static function writeUploadedImage(string $dir, UploadedFile $file): void
    {
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $filename = pathinfo($file->getClientFilename(), PATHINFO_BASENAME);
        $file->moveTo($dir.'/'.$filename);
    }

    public static function readImage(string $dir, string $fileName): ?string
    {
        $nodes = glob($dir.'/'.$fileName.'.*');
        foreach ($nodes as $node) {
            if (!is_file($node)) {
                continue;
            }

            if (self::checkSupportedImageFile($node)) {
                return substr($node, strlen($dir) + 1);
            }
        }

        return null;
    }

    public static function readSortedImages(string $dir, string $negativeFilter = ''): array
    {
        $nodes = glob($dir.'/*.*');
        $result = [];
        foreach ($nodes as $node) {
            if (!is_file($node)) {
                continue;
            }

            $basename = pathinfo($node, PATHINFO_BASENAME);
            if ($negativeFilter && str_starts_with($basename, $negativeFilter.'.')) {
                continue;
            }

            if (self::checkSupportedImageFile($node)) {
                $result[] = substr($node, strlen($dir) + 1);
            }
        }

        natcasesort($result);

        return array_values($result);
    }

    private static function checkSupportedImageFile(string $path): bool
    {
        foreach (PathHelper::SUPPORTED_IMAGE_EXTENSIONS as $supportedImageExtension) {
            if (str_ends_with($path, $supportedImageExtension)) {
                return true;
            }
        }

        return false;
    }

    public static function removeFile(string $path): void
    {
        unlink($path);
    }
}
