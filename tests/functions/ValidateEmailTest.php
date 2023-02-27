<?php
/**
* Test suite for functions in functions/form-validator.php.
*
* @group functions
*/

require_once __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use function Weblintreal\FormValidator\Functions\validateEmail;

class ValidateEmailTest extends TestCase
{
    public function testValidEmail()
    {
        $value = 'test@example.com';
        $field = 'Email';
        $param = null;

        $result = validateEmail($value, $field, $param);

        $this->assertTrue($result);
    }

    public function testInvalidEmail()
    {
        $value = 'notanemail';
        $field = 'Email';
        $param = null;

        $result = validateEmail($value, $field, $param);

        $this->assertEquals('Email must be a valid email address.', $result);
    }
}
