<?php

namespace DiniTheorie\Instructor\Utils;

class HtmlEmitter
{
    public function vite(string $entry): string
    {
        return "\n" . $this->jsTag($entry)
            . "\n" . $this->jsPreloadImports($entry)
            . "\n" . $this->cssTag($entry);
    }

// Some dev/prod mechanism would exist in your project

    private function isDev(string $entry): bool
    {
        if ($_SERVER['HTTP_HOST'] !== 'localhost') {
            return false;
        }

        // check if vite server is online
        static $exists = null;
        if (null !== $exists) {
            return $exists;
        }
        $handle = curl_init(VITE_HOST . '/' . $entry);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_NOBODY, true);

        curl_exec($handle);
        $error = curl_errno($handle);
        curl_close($handle);

        return $exists = !$error;
    }

    private function jsTag(string $entry): string
    {
        $url = $this->isDev($entry)
            ? VITE_HOST . '/' . $entry
            : $this->assetUrl($entry);

        if (!$url) {
            return '';
        }

        return '<script type="module" crossorigin src="'
            . $url
            . '"></script>';
    }

    private function jsPreloadImports(string $entry): string
    {
        if ($this->isDev($entry)) {
            return '';
        }

        $res = '';
        foreach ($this->importsUrls($entry) as $url) {
            $res .= '<link rel="modulepreload" href="'
                . $url
                . '">';
        }

        return $res;
    }

    private function cssTag(string $entry): string
    {
        // not needed on dev, it's inject by Vite
        if ($this->isDev($entry)) {
            return '';
        }

        $tags = '';
        foreach ($this->cssUrls($entry) as $url) {
            $tags .= '<link rel="stylesheet" href="'
                . $url
                . '">';
        }

        return $tags;
    }

// Helpers to locate files

    private function getManifest(): array
    {
        $content = file_get_contents(__DIR__ . '/../../public/manifest.json');

        return json_decode($content, true);
    }

    private function assetUrl(string $entry): string
    {
        $manifest = $this->getManifest();

        return isset($manifest[$entry])
            ? '/' . $manifest[$entry]['file']
            : '';
    }

    private function importsUrls(string $entry): array
    {
        $urls = [];
        $manifest = $this->getManifest();

        if (!empty($manifest[$entry]['imports'])) {
            foreach ($manifest[$entry]['imports'] as $imports) {
                $urls[] = '/' . $manifest[$imports]['file'];
            }
        }

        return $urls;
    }

    private function cssUrls(string $entry): array
    {
        $urls = [];
        $manifest = $this->getManifest();

        if (!empty($manifest[$entry]['css'])) {
            foreach ($manifest[$entry]['css'] as $file) {
                $urls[] = '/' . $file;
            }
        }

        return $urls;
    }

}
