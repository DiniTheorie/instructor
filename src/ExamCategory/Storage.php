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

use DiniTheorie\Instructor\Repository;
use Symfony\Component\Yaml\Yaml;

class Storage
{
    private Repository $database;

    public const QUESTIONS_PATH = Repository::REPO_PATH.'/template/questions';

    public function __construct(Repository $database)
    {
        $this->database = $database;
    }

    public function getCategories(): array
    {
        $nodes = scandir(self::QUESTIONS_PATH);

        $categoryFolders = [];
        foreach ($nodes as $node) {
            if (!is_dir($node)) {
                continue;
            }

            $categoryFolders[] = $node;
        }

        return $categoryFolders;
    }

    public function getCategory(string $id): array
    {
        $categoryPath = self::QUESTIONS_PATH.'/'.$id;
        $translations = $this->readTranslations($categoryPath);

        return ['id' => $id, 'translations' => $translations];
    }

    public function addCategory(array $category): void
    {
        $categoryPath = self::QUESTIONS_PATH.'/'.$category['id'];
        mkdir($categoryPath);

        $this->storeTranslations($categoryPath, $category['translations']);
    }

    public function storeCategory(string $id, array $category): void
    {
        $categoryPath = self::QUESTIONS_PATH.'/'.$id;
        $nodes = glob($categoryPath.'/introduction.*.yml');
        foreach ($nodes as $node) {
            unlink($node);
        }

        $this->storeTranslations($categoryPath, $category['translations']);
    }

    public function removeCategory(string $id): void
    {
        $categoryPath = self::QUESTIONS_PATH.'/'.$id;
        rmdir($categoryPath);
    }

    private function readTranslations(string $categoryPath): array
    {
        $nodes = glob($categoryPath.'/introduction.*.yml');

        $translations = [];
        foreach ($nodes as $node) {
            if (!is_file($node)) {
                continue;
            }

            $language = substr(substr($node, -6), 0, 2); // read out de.yml
            $content = file_get_contents($node);
            $parsedContent = Yaml::parse($content);
            $parsedContent['language'] = $language;

            $translations[] = $parsedContent;
        }

        return $translations;
    }

    private function storeTranslations(string $path, array $translations): void
    {
        foreach ($translations as $translation) {
            $language = $translation['language'];
            unset($translation['language']);

            $content = Yaml::dump($translation);
            $filePath = $path.'/introduction.'.$language.'.yml';
            file_put_contents($filePath, $content);
        }

        $this->database->store();
    }
}
