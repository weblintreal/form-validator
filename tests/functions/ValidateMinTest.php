<?php
/**
* Test suite for functions in functions/form-validator.php.
*
* @group functions
*/

require_once __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use function Weblintreal\FormValidator\Functions\validateMin;

class ValidateMinTest extends TestCase
{
    public function testValidateMinReturnsTrueWhenValueIsLongerThanMinLength()
    {
        $value = 'abcde';
        $field = 'Name';
        $minLength = 3;

        $result = validateMin($value, $field, $minLength);

        $this->assertTrue($result);
    }

    public function testValidateMinReturnsErrorMessageWhenValueIsShorterThanMinLength()
    {
        $value = 'ab';
        $field = 'Name';
        $minLength = 3;

        $result = validateMin($value, $field, $minLength);

        $this->assertEquals('Name must be at least 3 characters.', $result);
    }

    public function testValidateMinReturnsTrueWhenValueIsExactlyMinLength()
    {
        $value = 'abc';
        $field = 'Name';
        $minLength = 3;

        $result = validateMin($value, $field, $minLength);

        $this->assertTrue($result);
    }

    public function testValidateMinTrimsInputBeforeValidation()
    {
        $value = '  abc  ';
        $field = 'Name';
        $minLength = 3;

        $result = validateMin($value, $field, $minLength);

        $this->assertTrue($result);
    }
}
