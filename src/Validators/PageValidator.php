<?php

namespace Sihae\Validators;

use Schemer\Validator as V;

/**
 * Validator for new Pages
 */
class PageValidator implements Validator
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
            // a title can't be purely numeric
            'title' => V::text()->min(3)->max(50)->regex('/[a-z]/i'),
            'body' => V::text()->min(10),
        ]);
    }

    /**
     * @param array $pageDetails
     * @return boolean
     */
    public function isValid(array $pageDetails) : bool
    {
        $result = $this->validator->validate($pageDetails);

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
