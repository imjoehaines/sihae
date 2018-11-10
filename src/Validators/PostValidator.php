<?php declare(strict_types=1);

namespace Sihae\Validators;

/**
 * Validator for new Posts
 */
class PostValidator
{
    /**
     * @var array
     */
    private $errors = [];

    /**
     * @param array $postDetails
     * @return bool
     */
    public function isValid(array $postDetails) : bool
    {
        $this->errors = [];

        if (strlen($postDetails['title']) < 3) {
            $this->errors[] = 'Title: not at least 3 characters';
        }

        if (strlen($postDetails['title']) > 50) {
            $this->errors[] = 'Title: more than 50 characters';
        }

        if (strlen($postDetails['body']) < 10) {
            $this->errors[] = 'Body: not at least 10 characters';
        }

        return empty($this->errors);
    }

    /**
     * @return array
     */
    public function getErrors() : array
    {
        return $this->errors;
    }
}
