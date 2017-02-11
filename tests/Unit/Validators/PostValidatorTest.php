<?php declare(strict_types=1);

namespace Sihae\Tests\Unit\Validators;

use PHPUnit\Framework\TestCase;

use Sihae\Validators\PostValidator;

class PostValidatorTest extends TestCase
{
    /**
     * @return void
     */
    public function testItPassesWhenTitleAndBodyAreValidLengths() : void
    {
        $validator = new PostValidator();

        $data = ['title' => '123', 'body' => '1234567890'];

        $this->assertTrue($validator->isValid($data));
        $this->assertEmpty($validator->getErrors());
    }

    /**
     * @return void
     */
    public function testItFailsWhenTitleIsBelowMinimumLength() : void
    {
        $validator = new PostValidator();

        $data = ['title' => '12', 'body' => '1234567890'];

        $expected = [
            'Title: not at least 3 characters',
        ];

        $this->assertFalse($validator->isValid($data));
        $this->assertSame($expected, $validator->getErrors());
    }

    /**
     * @return void
     */
    public function testItFailsWhenBodyIsBelowMinimumLength() : void
    {
        $validator = new PostValidator();

        $data = ['title' => '123', 'body' => '123456789'];

        $expected = [
            'Body: not at least 10 characters',
        ];

        $this->assertFalse($validator->isValid($data));
        $this->assertSame($expected, $validator->getErrors());
    }

    /**
     * @return void
     */
    public function testItFailsWhenTitleAndBodyAreBelowMinimumLengths() : void
    {
        $validator = new PostValidator();

        $data = ['title' => '12', 'body' => '123456789'];

        $expected = [
            'Title: not at least 3 characters',
            'Body: not at least 10 characters',
        ];

        $this->assertFalse($validator->isValid($data));
        $this->assertSame($expected, $validator->getErrors());
    }

    /**
     * @return void
     */
    public function testItFailsWhenTitleIsAboveMaximumLength() : void
    {
        $validator = new PostValidator();

        $data = ['title' => str_repeat('a', 51), 'body' => '1234567890'];

        $expected = [
            'Title: more than 50 characters',
        ];

        $this->assertFalse($validator->isValid($data));
        $this->assertSame($expected, $validator->getErrors());
    }

    /**
     * @return void
     */
    public function testItPassesWhenTitleIsJustBelowMaximumLength() : void
    {
        $validator = new PostValidator();

        $data = ['title' => str_repeat('a', 50), 'body' => '1234567890'];

        $this->assertTrue($validator->isValid($data));
        $this->assertEmpty($validator->getErrors());
    }
}
