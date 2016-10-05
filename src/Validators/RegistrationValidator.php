<?php

namespace Sihae\Validators;

use Schemer\Result;
use Schemer\Validator as V;
use Schemer\Formatter as F;

class RegistrationValidator implements Validator
{
    private $validator;
    private $formatter;
    private $errors = [];

    public function __construct()
    {
        $this->validator = V::assoc([
            'Username' => V::text()->min(3)->max(50)->alphanum(),
            'Password' => V::text()->min(7),
            'Password confirmation' => V::text()->min(7),
        ]);

        $this->formatter = F::assoc([
            'username' => F::text(),
            'password' => F::text(),
            'password_confirmation' => F::text(),
        ])->rename('username', 'Username')
          ->rename('password', 'Password')
          ->rename('password_confirmation', 'Password confirmation');
    }

    public function isValid(array $userDetails) : bool
    {
        $detailsToValidate = $this->formatter->format($userDetails);
        $result = $this->validator->validate($detailsToValidate);

        if ($userDetails['password'] !== $userDetails['password_confirmation']) {
            $result = Result::failure('Passwords did not match')->concat($result);
        }

        $this->errors = $result->errors();

        return !$result->isError();
    }

    public function getErrors() : array
    {
        return $this->errors;
    }
}
