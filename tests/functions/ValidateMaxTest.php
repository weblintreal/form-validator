<?php
/**
* Test suite for functions in functions.php.
*
* @group functions
*/
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use function Weblintreal\FormValidator\Functions\validateMax;

class ValidateMaxTest extends TestCase
{
    public function testValidateMin()
    {
        $fieldName = 'Test Field';
        $value = 'abcdefghijkl';
        $rules = ['max:10'];
        $messages = [
            'max' => ':attribute must be at most :max characters long.',
        ];

        $errors = validateMax($fieldName, $value, $rules, $messages);

        $this->assertEquals(['Test Field' => 'Test Field must be at most 10 characters long.'], $errors);
    }
}
