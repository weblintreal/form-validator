<?php
/**
* Test suite for functions in functions/form-validator.php.
*
* @group functions
*/

require_once __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use function Weblintreal\FormValidator\Functions\validateRegex;

class ValidateRegexTest extends TestCase
{
    public function testValidRegex()
    {
        $value = '123456';
        $field = 'test_field';
        $pattern = '/^[0-9]+$/';
        $result = validateRegex($value, $field, $pattern);
        $this->assertTrue($result);
    }

    public function testInvalidRegex()
    {
        $value = '12a3456';
        $field = 'test_field';
        $pattern = '/^[0-9]+$/';
        $result = validateRegex($value, $field, $pattern);
        $this->assertEquals("$field is invalid.", $result);
    }

    public function testRegexWithSpecialCharacters()
    {
        $value = 'user@example.com';
        $field = 'email';
        $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
        $result = validateRegex($value, $field, $pattern);
        $this->assertTrue($result);
    }

    public function testRegexWithWhitespace()
    {
        $value = 'hello world';
        $field = 'test_field';
        $pattern = '/^[a-zA-Z]+$/';
        $result = validateRegex($value, $field, $pattern);
        $this->assertEquals("$field is invalid.", $result);
    }
}

