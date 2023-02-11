<?php
/**
* Test suite for functions in functions.php.
*
* @group functions
*/
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use function Weblintreal\FormValidator\Functions\validateForm;
use function Weblintreal\FormValidator\Functions\validateRequired;

class ValidateFormTest extends TestCase
{
    public function testValidData()
    {
        $data = [
            'username' => 'johndoe',
            'email' => 'johndoe@example.com',
            'password' => '123456',
        ];
    
        $rules = [
            'username' => ['required'],
            'email' => ['required'],
            'password' => ['required'],
        ];
    
        $messages = [
            'required' => ':attribute is required.'
        ];

        $errors = validateForm($data, $rules, $messages);

        $this->assertEquals([], $errors);
    }

    public function testInvalidData()
    {
        $data = [
            'username' => '',
            'email' => '',
            'password' => '',
        ];

        $rules = [
            'username' => ['required'],
            'email' => ['required'],
            'password' => ['required'],
        ];

        $messages = [
            'username' => 'username is required.',
            'email' => 'email is required.',
            'password' => 'password is required.'
        ];

        $errors = validateForm($data, $rules, $messages);

        $this->assertEquals(['username' => 'username is required.',
        'email' => 'email is required.',
        'password' => 'password is required.'], $errors);
    }
}
