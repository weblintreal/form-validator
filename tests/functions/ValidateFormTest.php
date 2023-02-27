<?php
/**
* Test suite for functions in functions/form-validator.php.
*
* @group functions
*/

require_once __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use function Weblintreal\FormValidator\Functions\validateForm;


class ValidateFormTest extends TestCase
{
    // public function testValidateFormReturnsNoErrorsWhenValidDataIsPassed()
    // {
    //     $formData = [
    //         'name' => 'John Doe'
    //     ];

    //     $rules = [
    //         'name' => 'required'
    //     ];

    //     $errors = validateForm($formData, $rules);

    //     $this->assertEquals([
    //         'name' => true
    //     ], $errors);
    // }

    // public function testValidateFormReturnsErrorsWhenInvalidDataIsPassed()
    // {
    //     $formData = [
    //         'name' => ''
    //     ];

    //     $rules = [
    //         'name' => 'required'
    //     ];

    //     $errors = validateForm($formData, $rules);

    //     $this->assertEquals([
    //         'name' => 'Name is required.'
    //     ], $errors);
    // }

    public function testValidateFormThrowsExceptionWhenInvalidRuleIsPassed()
    {
        $formData = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password123',
            'confirm_password' => 'password123',
        ];

        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password',
            'invalid_field' => 'invalid_rule',
        ];

        $this->expectException(Exception::class);
        validateForm($formData, $rules);
    }
}