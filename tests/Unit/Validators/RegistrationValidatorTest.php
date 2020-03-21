<?php

declare(strict_types=1);

namespace Sihae\Tests\Unit\Validators;

use PHPUnit\Framework\TestCase;

use Sihae\Validators\RegistrationValidator;

class RegistrationValidatorTest extends TestCase
{
    /**
     * @return void
     */
    public function testItPassesWhenUsernameAndPasswordAreValidLengthsAndConfirmationMatches(): void
    {
        $validator = new RegistrationValidator();

        $data = [
            'username' => 'billy',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ];

        $result = $validator->validate($data);

        $this->assertTrue($result->isSuccess());
        $this->assertEmpty($result->getErrors());
    }

    /**
     * @return void
     */
    public function testItFailsWhenUsernameIsTooShort(): void
    {
        $validator = new RegistrationValidator();

        $data = [
            'username' => 'bi',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ];

        $expected = [
            'Username: not at least 3 characters',
        ];

        $result = $validator->validate($data);

        $this->assertFalse($result->isSuccess());
        $this->assertSame($expected, $result->getErrors());
    }

    /**
     * @return void
     */
    public function testItFailsWhenPasswordIsTooShort(): void
    {
        $validator = new RegistrationValidator();

        $data = [
            'username' => 'billy',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ];

        $expected = [
            'Password: not at least 7 characters',
            'Password confirmation: not at least 7 characters',
        ];

        $result = $validator->validate($data);

        $this->assertFalse($result->isSuccess());
        $this->assertSame($expected, $result->getErrors());
    }

    /**
     * @return void
     */
    public function testItFailsWhenPasswordsDoNotMatch(): void
    {
        $validator = new RegistrationValidator();

        $data = [
            'username' => 'billy',
            'password' => 'secret123',
            'password_confirmation' => 'not secret123',
        ];

        $expected = [
            'Passwords didn\'t match!',
        ];

        $result = $validator->validate($data);

        $this->assertFalse($result->isSuccess());
        $this->assertSame($expected, $result->getErrors());
    }

    /**
     * @return void
     */
    public function testItFailsWhenUsernameIsTooLong(): void
    {
        $validator = new RegistrationValidator();

        $data = [
            'username' => str_repeat('a', 51),
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ];

        $expected = [
            'Username: more than 50 characters',
        ];

        $result = $validator->validate($data);

        $this->assertFalse($result->isSuccess());
        $this->assertSame($expected, $result->getErrors());
    }

    /**
     * @return void
     */
    public function testItFailsWhenUsernameIsNotAlphaNumeric(): void
    {
        $validator = new RegistrationValidator();

        $data = [
            'username' => '][][][][][][][][][][][][][',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ];

        $expected = [
            'Username: not alphanumeric',
        ];

        $result = $validator->validate($data);

        $this->assertFalse($result->isSuccess());
        $this->assertSame($expected, $result->getErrors());
    }
}
