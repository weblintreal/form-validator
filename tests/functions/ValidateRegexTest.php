<?php
/**
* Test suite for functions in functions.php.
*
* @group functions
*/
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use function Weblintreal\FormValidator\Functions\validateRegex;

class ValidateRegexTest extends TestCase
{
    public function testValidateRegex()
    {
        $fieldName = 'Test Field';
        $value = '123';
        $rules = ['regex:/^[a-z]+$/'];
        $messages = [
            'regex' => ':attribute format is invalid.',
        ];

        $errors = validateRegex($fieldName, $value, $rules, $messages);

        $this->assertEquals(['Test Field' => 'Test Field format is invalid.'], $errors);
    }
}