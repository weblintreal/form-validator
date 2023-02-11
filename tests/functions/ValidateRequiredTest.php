<?php
/**
* Test suite for functions in functions.php.
*
* @group functions
*/
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use function Weblintreal\FormValidator\Functions\validateRequired;

class ValidateRequiredTest extends TestCase
{
    public function testValidateRequired()
    {
        $fieldName = 'Test Field';
        $value = '';
        $rules = ['required'];
        $messages = [
            'required' => ':attribute is required.',
        ];

        $errors = validateRequired($fieldName, $value, $rules, $messages);

        $this->assertEquals(['Test Field' => 'Test Field is required.'], $errors);
    }
}

