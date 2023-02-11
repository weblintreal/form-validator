<?php
/**
* Test suite for functions in functions.php.
*
* @group functions
*/
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use function Weblintreal\FormValidator\Functions\validateEmail;

class ValidateEmailTest extends TestCase
{
    public function testValidateEmail()
    {
        $fieldName = 'Test Field';
        $value = 'invalid-email';
        $rules = ['email'];
        $messages = [
            'email' => 'Invalid :attribute format.',
        ];

        $errors = validateEmail($fieldName, $value, $rules, $messages);

        $this->assertEquals(['Test Field' => 'Invalid Test Field format.'], $errors);
    }
}
