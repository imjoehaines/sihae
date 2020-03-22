<?php

declare(strict_types=1);

namespace Sihae\Validators;

/**
 * Validator for new Users
 */
final class RegistrationValidator implements Validator
{
    /**
     * @param array<string, string> $userDetails
     * @return Result
     */
    public function validate(array $userDetails): Result
    {
        $errors = [];

        if (strlen($userDetails['username']) < 3) {
            $errors[] = 'Username: not at least 3 characters';
        }

        if (strlen($userDetails['username']) > 50) {
            $errors[] = 'Username: more than 50 characters';
        }

        if (preg_match('/^[[:alnum:]]+$/', $userDetails['username']) !== 1) {
            $errors[] = 'Username: not alphanumeric';
        }

        if (strlen($userDetails['password']) < 7) {
            $errors[] = 'Password: not at least 7 characters';
        }

        if (strlen($userDetails['password_confirmation']) < 7) {
            $errors[] = 'Password confirmation: not at least 7 characters';
        }

        if ($userDetails['password'] !== $userDetails['password_confirmation']) {
            $errors[] = 'Passwords didn\'t match!';
        }

        return Result::from($errors);
    }
}
