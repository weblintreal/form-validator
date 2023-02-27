<?php
/**
* Test suite for functions in functions/form-validator.php.
*
* @group functions
*/

require_once __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use function Weblintreal\FormValidator\Functions\validateExactLength;

class ValidateExactLengthTest extends TestCase
{
    public function testExactLengthValid()
    {
        $result = validateExactLength('123456', 'Test Field', 6);
        $this->assertTrue($result);
    }

    public function testExactLengthInvalid()
    {
        $result = validateExactLength('12345', 'Test Field', 6);
        $this->assertEquals('Test Field must be exactly 6 characters.', $result);
    }

    public function testExactLengthWithWhitespace()
    {
        $result = validateExactLength('  123456  ', 'Test Field', 6);
        $this->assertTrue($result);
    }

    public function testExactLengthWithSpecialCharacters()
    {
        $result = validateExactLength('@#&123', 'Test Field', 6);
        $this->assertEquals('Test Field must be exactly 6 characters.', $result);
    }
}
