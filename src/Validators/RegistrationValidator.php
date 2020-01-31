<?php declare(strict_types=1);

namespace Sihae\Validators;

/**
 * Validator for new Users
 */
class RegistrationValidator implements Validator
{
    /**
     * @var array<string>
     */
    private $errors = [];

    /**
     * @param array<string, string> $userDetails
     * @return bool
     */
    public function isValid(array $userDetails) : bool
    {
        $this->errors = [];

        if (strlen($userDetails['username']) < 3) {
            $this->errors[] = 'Username: not at least 3 characters';
        }

        if (strlen($userDetails['username']) > 50) {
            $this->errors[] = 'Username: more than 50 characters';
        }

        if (preg_match('/^[[:alnum:]]+$/', $userDetails['username']) !== 1) {
            $this->errors[] = 'Username: not alphanumeric';
        }

        if (strlen($userDetails['password']) < 7) {
            $this->errors[] = 'Password: not at least 7 characters';
        }

        if (strlen($userDetails['password_confirmation']) < 7) {
            $this->errors[] = 'Password confirmation: not at least 7 characters';
        }

        if ($userDetails['password'] !== $userDetails['password_confirmation']) {
            $this->errors[] = 'Passwords didn\'t match!';
        }

        return count($this->errors) === 0;
    }

    /**
     * @return array<string>
     */
    public function getErrors() : array
    {
        return $this->errors;
    }
}
