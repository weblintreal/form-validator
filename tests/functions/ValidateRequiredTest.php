<?php
/**
* Test suite for functions in functions/form-validator.php.
*
* @group functions
*/

require_once __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use function Weblintreal\FormValidator\Functions\validateRequired;

class ValidateRequiredTest extends TestCase
{
    public function testEmptyStringReturnsErrorMessage()
    {
        $result = validateRequired('', 'Name', '');
        $this->assertEquals('Name is required.', $result);
    }

    public function testNullValueReturnsErrorMessage()
    {
        $result = validateRequired(null, 'Email', '');
        $this->assertEquals('Email is required.', $result);
    }

    public function testWhitespaceReturnsErrorMessage()
    {
        $result = validateRequired('   ', 'Phone', '');
        $this->assertEquals('Phone is required.', $result);
    }

    public function testValidInputReturnsTrue()
    {
        $result = validateRequired('John Doe', 'Name','');
        $this->assertTrue($result);
    }
}
