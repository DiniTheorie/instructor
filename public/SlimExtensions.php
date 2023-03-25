<?php

use Slim\Psr7\Request;
use Slim\Psr7\Response;

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
        $response->getBody()->write(json_encode($body));

        return $response
            ->withStatus($statusCode)
            ->withHeader('Content-Type', 'application/json');
    }
}
