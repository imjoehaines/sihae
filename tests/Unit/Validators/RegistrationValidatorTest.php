<?php

namespace Sihae\Tests\Unit\Formatters;

use PHPUnit\Framework\TestCase;
use Sihae\Validators\RegistrationValidator;

class RegistrationValidatorTest extends TestCase
{
    public function testAnEmptyArrayIsInvalid()
    {
        $registrationValidator = new RegistrationValidator();

        $this->assertFalse($registrationValidator->isValid([]));
    }

    public function testItRequiresAtLeastThreeCharactersInAUsername()
    {
        $registrationValidator = new RegistrationValidator();

        $data = ['username' => 'Hi'];

        $expected = ['Username: not at least 3 characters'];

        $this->assertFalse($registrationValidator->isValid($data));
        $this->assertArraySubset($expected, $registrationValidator->getErrors());
    }

    public function testItRequiresAtLeastSevenCharactersInAPassword()
    {
        $registrationValidator = new RegistrationValidator();

        $data = ['password' => 'passwo'];

        $expected = [2 => 'Password: not at least 7 characters'];

        $this->assertFalse($registrationValidator->isValid($data));
        $this->assertArraySubset($expected, $registrationValidator->getErrors());
    }

    public function testItRequiresAtLeastSevenCharactersInAPasswordConfirmation()
    {
        $registrationValidator = new RegistrationValidator();

        $data = ['password_confirmation' => 'passwo'];

        $expected = [3 => 'Password confirmation: not at least 7 characters'];

        $this->assertFalse($registrationValidator->isValid($data));
        $this->assertArraySubset($expected, $registrationValidator->getErrors());
    }

    public function testItRequiresPasswordConfirmationToMatchPassword()
    {
        $registrationValidator = new RegistrationValidator();

        $data = ['password' => 'password', 'password_confirmation' => 'notpassword'];

        $expected = [2 => 'Passwords didn\'t match!'];

        $this->assertFalse($registrationValidator->isValid($data));
        $this->assertArraySubset($expected, $registrationValidator->getErrors());
    }

    public function testItAllowsCorrectCredentials()
    {
        $registrationValidator = new RegistrationValidator();

        $data = ['username' => 'fluffybunny2312', 'password' => 'password', 'password_confirmation' => 'password'];

        $expected = [];

        $this->assertTrue($registrationValidator->isValid($data));
        $this->assertSame($expected, $registrationValidator->getErrors());
    }
}
