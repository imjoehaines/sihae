<?php

namespace Sihae\Tests\Unit\Formatters;

use PHPUnit\Framework\TestCase;
use Sihae\Validators\PostValidator;

class PostValidatorTest extends TestCase
{
    public function testAnEmptyArrayIsInvalid()
    {
        $postValidator = new PostValidator();

        $this->assertFalse($postValidator->isValid([]));
    }

    public function testItRequiresAtLeastThreeCharactersInATitle()
    {
        $postValidator = new PostValidator();

        $data = ['title' => 'Hi'];

        $expected = ['Title: not at least 3 characters'];

        $this->assertFalse($postValidator->isValid($data));
        $this->assertArraySubset($expected, $postValidator->getErrors());
    }

    public function testItRequiresLessThanFiftyCharactersInATitle()
    {
        $postValidator = new PostValidator();

        $data = ['title' => str_repeat('a', 51)];

        $expected = ['Title: more than 50 characters'];

        $this->assertFalse($postValidator->isValid($data));
        $this->assertArraySubset($expected, $postValidator->getErrors());
    }

    public function testItRequiresAtLeastTenCharactersInTheBody()
    {
        $postValidator = new PostValidator();

        $data = ['body' => 'hello bob'];

        $expected = [1 => 'Body: not at least 10 characters'];

        $this->assertFalse($postValidator->isValid($data));
        $this->assertArraySubset($expected, $postValidator->getErrors());
    }

    public function testItAllowsFiftyCharacterTitles()
    {
        $postValidator = new PostValidator();

        $data = ['body' => 'hello mary', 'title' => str_repeat('a', 50)];

        $expected = [];

        $this->assertTrue($postValidator->isValid($data));
        $this->assertArraySubset($expected, $postValidator->getErrors());
    }
}
