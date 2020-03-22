<?php

declare(strict_types=1);

namespace Sihae\Validators;

final class Result
{
    /**
     * @var array<string>
     */
    private array $errors;

    /**
     * @param array<string> $errors
     */
    private function __construct(array $errors)
    {
        $this->errors = $errors;
    }

    /**
     * @param array<string> $errors
     * @return Result
     */
    public static function from(array $errors): Result
    {
        return new Result($errors);
    }

    public function isSuccess(): bool
    {
        return $this->errors === [];
    }

    /**
     * @return array<string>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
