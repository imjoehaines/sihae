<?php

namespace Sihae\Validators;

interface Validator
{
    public function isValid(array $data) : bool;
    public function getErrors() : array;
}
