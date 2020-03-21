<?php

declare(strict_types=1);

namespace Sihae\Validators;

interface Validator
{
    /**
     * @param array<string, string> $data
     * @return Result
     */
    public function validate(array $data): Result;
}
