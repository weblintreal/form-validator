<?php
/**
* Form validation functions for the Form Validator package.
*
* This package provides a set of functions for validating form input data. These functions can be used
* in a procedural style for ease of use and fast development. The package is licensed under the MIT License.
*
* @package Weblintreal\FormValidator
* @version 0.0.4
* @author Weblintreal
* @license https://opensource.org/licenses/MIT MIT License
*/

namespace Weblintreal\FormValidator\Functions;

use Exception;
use PhpParser\Node\Expr\Throw_;

/**
 * Sanitize input data to prevent SQL injection, cross-site scripting (XSS), and other attacks.
 *
 * @param mixed $input The input data to sanitize.
 * @param string|null $type The type of data to sanitize.
 * @return mixed The sanitized input data.
 */
function sanitizeInput($input, $type = null)
{
    if (is_array($input)) {
        return array_map(function($data) use ($type) {
            return sanitizeInput($data, $type);
        }, $input);
    }

    if ($type !== null) {
        switch ($type) {
            case 'email':
                $input = filter_var($input, FILTER_SANITIZE_EMAIL);
                break;
            case 'url':
                $input = filter_var($input, FILTER_SANITIZE_URL);
                break;
            case 'int':
                $input = filter_var($input, FILTER_SANITIZE_NUMBER_INT);
                break;
            case 'float':
                $input = filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                break;
            case 'alpha':
                $input = preg_replace('/[^a-zA-Z]/', '', $input);
                break;
            case 'alphanumeric':
                $input = preg_replace('/[^a-zA-Z0-9]/', '', $input);
                break;
            case 'html':
                $input = filter_var($input, FILTER_SANITIZE_SPECIAL_CHARS);
                break;
            case 'string':
            default:
                $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
                break;
        }
    }

    // Trim the input
    $input = trim($input);

    // Remove backslashes (\)
    $input = stripslashes($input);

    // Convert special characters to HTML entities
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');

    // Prevent SQL injection
    if (is_string($input)) {
        $input = str_replace(["\\", "\0", "\n", "\r", "'", '"', "\x1a"], '', $input);
    }

    return $input;
}


/**
 * Sanitize form data based on a set of rules.
 * 
 * @param array $form_data The form data to sanitize.
 * @param array $sanitization_rules The sanitization rules for each field in the form data.
 * @return array The sanitized form data.
*/
function sanitizeForm($form_data, $sanitization_rules)
{
    $sanitized_form_data = [];

    foreach ($form_data as $field_name => $field_value) {
        if (isset($sanitization_rules[$field_name])) {
            $sanitization_rule = $sanitization_rules[$field_name];

            // Check if the field should be skipped
            if ($sanitization_rule === 'skip') {
                continue;
            }

            // Get the type and length of the field
            $field_type = isset($sanitization_rule['type']) ? $sanitization_rule['type'] : 'string';

            // Sanitize the field value
            $sanitized_field_value = sanitizeInput($field_value, $field_type);

            // Add the sanitized field value to the sanitized form data
            $sanitized_form_data[$field_name] = $sanitized_field_value;
        } else {
            // If there is no sanitization rule for the field, sanitize the field value as a string
            $sanitized_form_data[$field_name] = sanitizeInput($field_value);
        }
    }

    return $sanitized_form_data;
}


/**
 * Validate form data based on the given rules.
 *
 * @param array $formData The form data to validate.
 * @param array $rules The validation rules to apply.
 * @return array An array of errors for any invalid form fields.
 */
function validateForm($formData, $rules)
{
    $errors = [];
    $param = "";
    foreach ($rules as $field => $rule) {
        $errors[$field] = "";
        $value = $formData[$field] ?? '';
        $ruleset = is_array($rule) ? $rule : explode('|', $rule);
        foreach ($ruleset as $rule) {            
            if (strpos($rule, ':') !== false) {
                $params = explode(':', $rule);
                $param = (int) $params[1];
                $method = 'validate' . ucfirst(array_shift($params));
            } else {
                $method = 'validate' . ucfirst($rule);
            }

            if (!function_exists($method)) {
                $exception = 'Validation method not found: ' . $method;
                Throw new Exception($exception);
            }


            $temp_error = call_user_func_array($method, [$value, $field, $param]);
            if($temp_error != 1){
                $errors[$field] = $errors[$field] . "<br>" . $temp_error;
            }
        }
    }
    return $errors;
}


/**
 * Validates if the given value is not empty or whitespace.
 * @param string $value The value to be validated.
 * @param string $field The name of the field being validated.
 * @param string|null $param Optional additional parameter to be used in validation.
 * @return bool|string Returns true if the value is not empty or whitespace, otherwise returns an error message.
*/

function validateRequired($value, $field, $param)
{
    if (empty(trim($value))) {
        return "$field is required.";
    }
    return true;
}


/**
 * Validate that a given value is a valid email address.
 * @param string $value The value to validate.
 * @param string $field The name of the field being validated.
 * @param mixed $param Unused.
 * @return true|string True if the validation passes, otherwise an error message string.
*/
function validateEmail($value, $field, $param) {
    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
        return "$field must be a valid email address.";
    }
    return true;
}


/**

 * Validate that a given value is at least a certain length.
 * @param string $value The value to validate.
 * @param string $field The name of the field being validated.
 * @param int $minLength The minimum length required.
 * @return true|string True if the validation passes, otherwise an error message string.
*/
function validateMin($value, $field, $minLength) {
    if (strlen(trim($value)) < $minLength) {
        return "$field must be at least $minLength characters.";
    }
    return true;
}


/**
 * Validate that a given value is not longer than a certain length.
 * @param string $value The value to validate.
 * @param string $field The name of the field being validated.
 * @param int $maxLength The maximum length allowed.
 * @return true|string True if the validation passes, otherwise an error message string.
*/
function validateMax($value, $field, $maxLength) {
    if (strlen(trim($value)) > $maxLength) {
        return "$field must not exceed $maxLength characters.";
    }
    return true;
}


/**
 * Validate that a field has an exact length.
 *
 * @param string $value The value of the field to validate.
 * @param string $field The name of the field being validated.
 * @param int $exactLength The exact length required for the field.
 * @return mixed True if the validation succeeds, an error message otherwise.
 */
function validateExactLength($value, $field, $exactLength) {
    if (strlen(trim($value)) !== $exactLength) {
        return "$field must be exactly $exactLength characters.";
    }
    return true;
}



/**
 * Validate that a value is a valid URL.
 *
 * @param string $value The value to validate.
 * @param string $field The name of the field being validated.
 * @param mixed $param Not used for this validation rule.
 * @return mixed True if the value is a valid URL, or an error message if it is not.
 */
function validateUrl($value, $field, $param)
{
    if (!filter_var($value, FILTER_VALIDATE_URL)) {
        return "$field must be a valid URL.";
    }
    return true;
}


/**
 * Validate a field value against a regular expression pattern.
 *
 * @param string $value The value to validate.
 * @param string $field The name of the field being validated.
 * @param string $pattern The regular expression pattern to match against.
 * @return mixed true if the validation passes, otherwise an error message.
 */
function validateRegex($value, $field, $pattern) {
    if (!preg_match($pattern, $value)) {
        return "$field is invalid.";
    }
    return true;
}
