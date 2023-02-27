<?php
/**
* Test suite for functions in functions/form-validator.php.
*
* @group functions
*/

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../src/functions/form-validator.php';
use PHPUnit\Framework\TestCase;
use function Weblintreal\FormValidator\Functions\sanitizeInput;

class SanitizeInputTest extends TestCase
{
    public function testSanitizeInputReturnsTrimmedAndStrippedString()
    {
        $input = "  This is a test input.  ";
        $expected = "This is a test input.";
        $this->assertEquals($expected, sanitizeInput($input));
    }

    public function testSanitizeInputRemovesSpecialCharacters()
    {
        $input = "This is a test input with 'special' characters: \";\\";
        $expected = "This is a test input with &#039;special&#039; characters: &quot;;";
        $this->assertEquals($expected, sanitizeInput($input));
    }

    public function testSanitizeInputReturnsFilteredEmail()
    {
        $input = "test@example.com";
        $expected = "test@example.com";
        $this->assertEquals($expected, sanitizeInput($input, 'email'));
    }

    public function testSanitizeInputReturnsFilteredUrl()
    {
        $input = "http://example.com";
        $expected = "http://example.com";
        $this->assertEquals($expected, sanitizeInput($input, 'url'));
    }

    public function testSanitizeInputReturnsFilteredInt()
    {
        $input = "1234";
        $expected = "1234";
        $this->assertEquals($expected, sanitizeInput($input, 'int'));
    }

    public function testSanitizeInputReturnsFilteredFloat()
    {
        $input = "3.14";
        $expected = "3.14";
        $this->assertEquals($expected, sanitizeInput($input, 'float'));
    }

    public function testSanitizeInputReturnsFilteredAlpha()
    {
        $input = "abc123";
        $expected = "abc";
        $this->assertEquals($expected, sanitizeInput($input, 'alpha'));
    }

    public function testSanitizeInputReturnsFilteredAlphanumeric()
    {
        $input = "abc123";
        $expected = "abc123";
        $this->assertEquals($expected, sanitizeInput($input, 'alphanumeric'));
    }

    public function testSanitizeInputReturnsFilteredHtml()
    {
        $input = "<script>alert('hello');</script>";
        $expected = "&amp;#60;script&amp;#62;alert(&amp;#39;hello&amp;#39;);&amp;#60;/script&amp;#62;";
        $this->assertEquals($expected, sanitizeInput($input, 'html'));
    }

    public function testSanitizeInputReturnsFilteredStringByDefault()
    {
        $input = "<script>alert('hello');</script>";
        $expected = "&lt;script&gt;alert(&#039;hello&#039;);&lt;/script&gt;";
        $this->assertEquals($expected, sanitizeInput($input));
    }
}