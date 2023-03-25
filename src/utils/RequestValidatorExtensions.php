<?php

/*
 * This file is part of the DiniTheorie project.
 *
 * (c) Florian Moser <git@famoser.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DiniTheorie\Instructor\utils;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class RequestValidatorExtensions
{

    public static function checkExactlyKeysSet(Request $request, array $array, ...$keys): void
    {
        if (count(array_keys($array)) !== count($keys)) {
            throw new HttpNotFoundException($request, 'you must provide exactly ' . count($keys) . ' keys.');
        }

        foreach ($keys as $key) {
            if (!key_exists($key, $array)) {
                throw new HttpNotFoundException($request, 'key ' . $key . ' expected, but not provided.');
            }
        }
    }

    public static function checkLanguageSupported(Request $request, string $language): void
    {
        if (!in_array($language, ['de', 'en', 'fr', 'it'])) {
            throw new HttpNotFoundException($request, 'language not supported');
        }
    }
}
