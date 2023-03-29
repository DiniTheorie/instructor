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

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Stream;

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

    public static function createFileResponse(Response $response, string $path, string $filename): Response
    {
        $fh = fopen($path, 'rb');
        $stream = new Stream($fh);

        return $response->withBody($stream)
            ->withHeader('Content-Disposition', 'attachment; filename='.$filename.';')
            ->withHeader('Expires', '0') // immediately expire
            ->withHeader('Content-Type', mime_content_type($path))
            ->withHeader('Content-Length', filesize($path));
    }
}
