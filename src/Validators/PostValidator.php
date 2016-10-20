<?php

namespace Sihae\Validators;

use Schemer\Validator as V;

/**
 * Validator for new Posts
 */
class PostValidator implements Validator
{
    /**
     * @var \Schemer\Validator\ValidatorInterface
     */
    private $validator;

    /**
     * @var array
     */
    private $errors = [];

    /**
     * Initialise validator rules
     */
    public function __construct()
    {
        $this->validator = V::assoc([
            'title' => V::text()->min(3)->max(50),
            'body' => V::text()->min(10),
        ]);
    }

    /**
     * @param array $postDetails
     * @return boolean
     */
    public function isValid(array $postDetails) : bool
    {
        $result = $this->validator->validate($postDetails);

        $this->errors = $result->map('ucfirst')->errors();

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
