<?php

declare(strict_types=1);

namespace Sihae\Validators;

/**
 * Validator for new Posts
 */
final class PostValidator implements Validator
{
    /**
     * @param array<string, string> $postDetails
     * @return Result
     */
    public function validate(array $postDetails): Result
    {
        $errors = [];

        if (strlen($postDetails['title']) < 3) {
            $errors[] = 'Title: not at least 3 characters';
        }

        if (strlen($postDetails['title']) > 50) {
            $errors[] = 'Title: more than 50 characters';
        }

        if (strlen($postDetails['body']) < 10) {
            $errors[] = 'Body: not at least 10 characters';
        }

        return Result::from($errors);
    }
}
