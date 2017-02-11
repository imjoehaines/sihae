<?php declare(strict_types=1);

namespace Sihae\Tests\Unit\Validators;

use PHPUnit\Framework\TestCase;

use Sihae\Validators\RegistrationValidator;

class RegistrationValidatorTest extends TestCase
{
    /**
     * @return void
     */
    public function testItPassesWhenUsernameAndPasswordAreValidLengthsAndConfirmationMatches() : void
    {
        $validator = new RegistrationValidator();

        $data = [
            'username' => 'billy',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ];

        $this->assertTrue($validator->isValid($data));
        $this->assertEmpty($validator->getErrors());
    }

    /**
     * @return void
     */
    public function testItFailsWhenUsernameIsTooShort() : void
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

        $this->assertFalse($validator->isValid($data));
        $this->assertSame($expected, $validator->getErrors());
    }

    /**
     * @return void
     */
    public function testItFailsWhenPasswordIsTooShort() : void
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

        $this->assertFalse($validator->isValid($data));
        $this->assertSame($expected, $validator->getErrors());
    }

    /**
     * @return void
     */
    public function testItFailsWhenPasswordsDoNotMatch() : void
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

        $this->assertFalse($validator->isValid($data));
        $this->assertSame($expected, $validator->getErrors());
    }

    /**
     * @return void
     */
    public function testItFailsWhenUsernameIsTooLong() : void
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

        $this->assertFalse($validator->isValid($data));
        $this->assertSame($expected, $validator->getErrors());
    }

    /**
     * @return void
     */
    public function testItFailsWhenUsernameIsNotAlphaNumeric() : void
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

        $this->assertFalse($validator->isValid($data));
        $this->assertSame($expected, $validator->getErrors());
    }
}
