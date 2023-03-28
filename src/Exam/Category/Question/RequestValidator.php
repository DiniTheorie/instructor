<?php

/*
 * This file is part of the DiniTheorie project.
 *
 * (c) Florian Moser <git@famoser.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DiniTheorie\Instructor\Exam\Category\Question;

use DiniTheorie\Instructor\Utils\RequestValidatorExtensions;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;

class RequestValidator
{
    public static function validateNewQuestionId(Request $request, Storage $storage, string $categoryId, string $questionId): void
    {
        if (!preg_match('/^\w+/i', $questionId)) {
            throw new HttpBadRequestException($request, 'category name invalid; use only alphanummeric or underscore');
        }

        $questions = $storage->getQuestionIds($categoryId);
        if (in_array($questionId, $questions, true)) {
            throw new HttpBadRequestException($request, 'category already exists');
        }
    }

    public static function validateQuestionId(Request $request, Storage $storage, string $categoryId, string $questionId): void
    {
        $questions = $storage->getQuestionIds($categoryId);
        if (!in_array($questionId, $questions, true)) {
            throw new HttpNotFoundException($request, 'category not found');
        }
    }

    public static function validateQuestion(Request $request, array $question): void
    {
        $metaKeys = ['exam', 'important', 'text_asa', 'image_asa', 'answer_1_correct', 'answer_2_correct', 'answer_3_correct'];
        RequestValidatorExtensions::checkExactlyKeysSet($request, $question['meta'], ...$metaKeys);
        RequestValidatorExtensions::checkKeysBoolean($request, $question['meta'], ...$metaKeys);

        foreach ($question['translations'] as $translation) {
            RequestValidatorExtensions::checkExactlyKeysSet($request, $translation, 'language', 'category', 'answer_1', 'answer_2', 'answer_3');
            RequestValidatorExtensions::checkLanguageSupported($request, $translation['language']);
        }
    }
}
