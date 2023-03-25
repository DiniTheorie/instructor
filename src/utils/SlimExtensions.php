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

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class SlimExtensions
{
    public const STATUS_OK = 200;
    public const STATUS_CREATED = 201;

    public static function parseJsonRequestBody(Request $request): array
    {
        $bodyContents = $request->getBody()->getContents();

        return json_decode($bodyContents, true);
    }

    public static function createJsonResponse(Response $response, array $body, int $statusCode = self::STATUS_OK): Response
    {
        $jsonContent = json_encode($body);
        $response->getBody()->write($jsonContent);

        return $response
            ->withStatus($statusCode)
            ->withHeader('Content-Type', 'application/json');
    }

    public static function createHTMLResponse(Response $response, string $htmlFilePath, int $statusCode = self::STATUS_OK): Response
    {
        $htmlContent = file_get_contents($htmlFilePath);
        $response->getBody()->write($htmlContent);

        return $response
            ->withStatus($statusCode)
            ->withHeader('Content-Type', 'text/html');
    }
}
