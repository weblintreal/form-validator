<?php
/**
* Test suite for functions in functions.php.
*
* @group functions
*/
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use function Weblintreal\FormValidator\Functions\validateExactLength;

class ValidateExactLengthTest extends TestCase
{
    public function testValidateExactLength()
    {
        $fieldName = 'Test Field';
        $value = 'abcde';
        $rules = ['exactLength:4'];
        $messages = [
            'exactLength' => ':attribute must be exactly :exactLength characters long.',
        ];

        $errors = validateExactLength($fieldName, $value, $rules, $messages);

        $this->assertEquals(['Test Field' => 'Test Field must be exactly 4 characters long.'], $errors);
    }
}

