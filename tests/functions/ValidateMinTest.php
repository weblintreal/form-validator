<?php
/**
* Test suite for functions in functions.php.
*
* @group functions
*/
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use function Weblintreal\FormValidator\Functions\validateMin;

class ValidateMinTest extends TestCase
{
    public function testValidateMin()
    {
        $fieldName = 'Test Field';
        $value = 'abc';
        $rules = ['min:4'];
        $messages = [
            'min' => ':attribute must have at least :min characters.',
        ];

        $errors = validateMin($fieldName, $value, $rules, $messages);

        $this->assertEquals(['Test Field' => 'Test Field must have at least 4 characters.'], $errors);
    }
}
