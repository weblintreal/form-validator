<?php
/**
* Test suite for functions in functions.php.
*
* @group functions
*/
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use function Weblintreal\FormValidator\Functions\validateUrl;

class ValidateUrlTest extends TestCase
{
    public function testValidateUrl()
    {
        $fieldName = 'Test Field';
        $value = 'example.com';
        $rules = ['url'];
        $messages = [
            'url' => ':attribute must be a valid URL.',
        ];

        $errors = validateUrl($fieldName, $value, $rules, $messages);

        $this->assertEquals(['Test Field' => 'Test Field must be a valid URL.'], $errors);
    }
}

