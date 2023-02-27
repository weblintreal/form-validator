<?php
/**
* Test suite for functions in functions/form-validator.php.
*
* @group functions
*/

require_once __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use function Weblintreal\FormValidator\Functions\sanitizeForm;


class SanitizeFormTest extends TestCase
{
    public function testSanitizeForm()
    {
        // Define form data
        $form_data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '(123) 456-7890',
            'message' => '<script>alert("Hello!");</script>'
        ];

        // Define sanitization rules
        $sanitization_rules = [
            'name' => [
                'type' => 'string'
            ],
            'email' => [
                'type' => 'email'
            ],
            'phone' => [
                'type' => 'string',
                'regex' => '/^\(\d{3}\) \d{3}-\d{4}$/'
            ],
            'message' => 'skip'
        ];

        // Sanitize the form data
        $sanitized_form_data = sanitizeForm($form_data, $sanitization_rules);

        // Assert that the name field was sanitized correctly
        $this->assertEquals('John Doe', $sanitized_form_data['name']);

        // Assert that the email field was sanitized correctly
        $this->assertEquals('john@example.com', $sanitized_form_data['email']);

        // Assert that the phone field was sanitized correctly
        $this->assertEquals('(123) 456-7890', $sanitized_form_data['phone']);

        // Assert that the message field was skipped
        $this->assertArrayNotHasKey('message', $sanitized_form_data);
    }
}
