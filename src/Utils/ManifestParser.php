<?php

namespace DiniTheorie\Instructor\Utils;

use DiniTheorie\Instructor\PathHelper;

class ManifestParser
{
    public function getAssetUrl(string $url): ?string
    {
        $manifest = $this->loadManifest();
        return "/" . $manifest[substr($url, 1)]['file'];
    }

    public function renderHtmlTags(string $entry): string
    {
        $out = [];
        if ($_SERVER['APP_ENV'] === 'dev' && $this->isViteServerRunning()) {
            $url = 'http://localhost:' . $_SERVER['VITE_DEV_PORT'] . '/' . $entry;
            $out[] = $this->renderCrossoriginJsTag($url);
        } else {
            $manifest = $this->loadManifest();

            $jsUrl = $manifest[$entry]['file'];
            $out[] = $this->renderJsTag($jsUrl);

            $jsImportUrls = $this->importsUrls($entry, $manifest);
            $out = array_merge($out, $this->renderJsPreloadImports($jsImportUrls));

            $cssUrls = $this->cssUrls($entry, $manifest);
            $out = array_merge($out, $this->renderCssTags($cssUrls));
        }

        return join("\n", $out);
    }

    private function loadManifest(): array
    {
        $content = file_get_contents(PathHelper::PUBLIC_DIR . '/manifest.json');
        return json_decode($content, true);
    }

    private function isViteServerRunning(): bool
    {
        if (!str_starts_with($_SERVER['HTTP_HOST'], 'localhost')) {
            return false;
        }

        // check vite server is running
        $connection = @fsockopen('localhost', $_SERVER['VITE_DEV_PORT']);
        if (is_resource($connection)) {
            fclose($connection);
            return true;
        }

        return false;
    }

    private function renderJsTag(string $url): string
    {
        return '<script type="module" src="/' . $url . '"></script>';
    }

    private function renderCrossoriginJsTag(string $url): string
    {
        return '<script type="module" crossorigin src="' . $url . '"></script>';
    }

    private function renderJsPreloadImports(array $urls): array
    {
        $res = [];
        foreach ($urls as $url) {
            $res[] = '<link rel="modulepreload" href="/' . $url . '">';
        }

        return $res;
    }

    private function renderCssTags(array $urls): array
    {
        $tags = [];
        foreach ($urls as $url) {
            $tags[] = '<link rel="stylesheet" href="/' . $url . '">';
        }

        return $tags;
    }


    private function importsUrls(string $entry, array $manifest): array
    {
        $urls = [];

        if (!empty($manifest[$entry]['imports'])) {
            foreach ($manifest[$entry]['imports'] as $imports) {
                $urls[] = $manifest[$imports]['file'];
            }
        }
        return $urls;
    }

    private function cssUrls(string $entry, array $manifest): array
    {
        return empty($manifest[$entry]['css']) ? [] : $manifest[$entry]['css'];
    }

}
