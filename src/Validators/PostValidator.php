<?php

namespace Sihae\Validators;

use Schemer\Validator as V;

class PostValidator implements Validator
{
    private $errors = [];

    public function __construct()
    {
        $this->validator = V::assoc([
            'title' => V::text()->min(3)->max(50),
            'body' => V::text()->min(10),
        ]);
    }

    public function isValid(array $postDetails) : bool
    {
        $result = $this->validator->validate($postDetails);

        $this->errors = $result->map('ucfirst')->errors();

        return !$result->isError();
    }

    public function getErrors() : array
    {
        return $this->errors;
    }
}