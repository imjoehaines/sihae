<?php

namespace Sihae\Validators;

use Schemer\Validator as V;
use Schemer\Formatter as F;

/**
 * Validator for new Users
 */
class RegistrationValidator implements Validator
{
    /**
     * @var \Schemer\Validator\ValidatorInterface
     */
    private $validator;

    /**
     * @var \Schemer\Validator\FormatterInterface
     */
    private $formatter;

    /**
     * @var array
     */
    private $errors = [];

    /**
     * Initialise validator rules and formatter
     */
    public function __construct()
    {
        $this->validator = V::assoc([
            'Username' => V::text()->min(3)->max(50)->alphanum(),
            'Password' => V::text()->min(7),
            'Password confirmation' => V::text()->min(7),
        ])->should(
            function ($values) {
                return $values['Password'] === $values['Password confirmation'];
            },
            'Passwords didn\'t match!'
        );

        $this->formatter = F::assoc([
            'username' => F::text(),
            'password' => F::text(),
            'password_confirmation' => F::text(),
        ])->renameMany([
            'username' => 'Username',
            'password' => 'Password',
            'password_confirmation' => 'Password confirmation'
        ]);
    }

    /**
     * @param array $userDetails
     * @return boolean
     */
    public function isValid(array $userDetails) : bool
    {
        $detailsToValidate = $this->formatter->format($userDetails);
        $result = $this->validator->validate($detailsToValidate);

        $this->errors = $result->errors();

        return !$result->isError();
    }

    /**
     * @return array
     */
    public function getErrors() : array
    {
        return $this->errors;
    }
}
