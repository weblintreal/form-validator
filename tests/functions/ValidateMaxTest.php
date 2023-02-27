<?php
/**
* Test suite for functions in functions/form-validator.php.
*
* @group functions
*/

require_once __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use function Weblintreal\FormValidator\Functions\validateMax;

class ValidateMaxTest extends TestCase
{
    public function testValidMax()
    {
        $value = 'John Doe';
        $field = 'Name';
        $maxLength = 10;

        $result = validateMax($value, $field, $maxLength);

        $this->assertTrue($result);
    }

    public function testExceedMax()
    {
        $value = 'Lorem ipsum dolor sit amet';
        $field = 'Comment';
        $maxLength = 20;

        $result = validateMax($value, $field, $maxLength);

        $this->assertEquals("$field must not exceed $maxLength characters.", $result);
    }

    public function testMaxWithTrim()
    {
        $value = '     hello     ';
        $field = 'Greeting';
        $maxLength = 5;

        $result = validateMax($value, $field, $maxLength);

        $this->assertEquals("$field must not exceed $maxLength characters.", $result);
    }
}
