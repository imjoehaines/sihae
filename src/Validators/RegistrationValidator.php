<?php

namespace Sihae\Validators;

use Schemer\Result;
use Schemer\Validator as V;

class RegistrationValidator implements Validator
{
    private $errors = [];

    public function __construct()
    {
        $this->validator = V::assoc([
            'Username' => V::text()->min(3)->max(50)->alphanum(),
            'Password' => V::text()->min(7),
            'Password confirmation' => V::text()->min(7),
        ]);
    }

    public function isValid(array $userDetails) : bool
    {
        // TODO this is because the array keys are used in the error messages - PR?
        $detailsToValidate = [
            'Username' => $userDetails['username'] ?? null,
            'Password' => $userDetails['password'] ?? null,
            'Password confirmation' => $userDetails['password_confirmation'] ?? null,
        ];

        $result = $this->validator->validate($detailsToValidate);

        if ($userDetails['password'] !== $userDetails['password_confirmation']) {
            $result = Result::failure('Password confirmation did not match password')->concat($result);
        }

        $this->errors = $result->errors();

        return !$result->isError();
    }

    public function getErrors() : array
    {
        return $this->errors;
    }
}
