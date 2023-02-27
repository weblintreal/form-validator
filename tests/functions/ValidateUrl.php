<?php
/**
* Test suite for functions in functions/form-validator.php.
*
* @group functions
*/

require_once __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use function Weblintreal\FormValidator\Functions\validateUrl;

class ValidateUrlTest extends TestCase
{
    public function testValidUrl()
    {
        $result = validateUrl('https://www.example.com', 'url', null);
        $this->assertTrue($result);
    }
    
    public function testInvalidUrl()
    {
        $result = validateUrl('invalid url', 'url', null);
        $this->assertEquals($result, 'url must be a valid URL.');
    }
    
    public function testNullValue()
    {
        $result = validateUrl(null, 'url', null);
        $this->assertEquals($result, 'url must be a valid URL.');
    }
}
