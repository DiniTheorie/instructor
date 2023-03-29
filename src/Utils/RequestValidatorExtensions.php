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
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Psr7\UploadedFile;

class RequestValidatorExtensions
{
    public static function checkFileUploadSuccessful(Request $request, ?UploadedFile $file): void
    {
        if (!$file) {
            throw new HttpBadRequestException($request, 'file upload failed; no file was received by the server.');
        }

        if (UPLOAD_ERR_OK !== $file->getError()) {
            throw new HttpBadRequestException($request, 'file upload failed with code '.$file->getError().'.');
        }
    }

    public static function checkSupportedImageFile(Request $request, ?UploadedFile $file): void
    {
        foreach (PathHelper::SUPPORTED_IMAGE_EXTENSIONS as $extension) {
            if (str_ends_with($file->getClientFilename(), $extension)) {
                return;
            }
        }

        $supportedExtensions = join(',', PathHelper::SUPPORTED_IMAGE_EXTENSIONS);
        throw new HttpBadRequestException($request, 'Filetype of '.$file->getClientFilename().' not supported. Supported are only: '.$supportedExtensions);
    }

    public static function checkExactlyKeysSet(Request $request, array $array, array $requiredKeys, array $optionalKeys = []): void
    {
        foreach ($requiredKeys as $key) {
            if (!key_exists($key, $array)) {
                throw new HttpBadRequestException($request, 'key '.$key.' expected, but not provided.');
            }
        }

        foreach (array_keys($array) as $key) {
            if (!in_array($key, $requiredKeys, true) && !in_array($key, $optionalKeys, true)) {
                throw new HttpBadRequestException($request, 'the key '.$key.' is invalid.');
            }
        }
    }

    public static function checkKeysBoolean(Request $request, array $array, array $keys): void
    {
        foreach ($keys as $key) {
            if (!is_bool($array[$key])) {
                throw new HttpBadRequestException($request, 'key '.$key.' should be boolean, but value was '.$array[$key]);
            }
        }
    }

    public static function checkLanguageSupported(Request $request, string $language): void
    {
        if (!in_array($language, ['de', 'en', 'fr', 'it'])) {
            throw new HttpBadRequestException($request, 'language not supported');
        }
    }
}
