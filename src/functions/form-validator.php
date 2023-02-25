<?php
/**
* Form validation functions for the Form Validator package.
*
* This package provides a set of functions for validating form input data. These functions can be used
* in a procedural style for ease of use and fast development. The package is licensed under the MIT License.
*
* @package Weblintreal\FormValidator
* @version 1.0.0
* @author Weblintreal
* @license https://opensource.org/licenses/MIT MIT License
*/

namespace Weblintreal\FormValidator\Functions;



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
 * Validate the form data.
 * 
 * @param array $data The form data to validate.
 * @param array $rules The validation rules to apply to each field.
 * @param array $messages The custom error messages for each rule.
 * @return array An array of error messages.
 */
function validateForm(array $data, array $rules, array $messages): array
{
    $errors = [];
    foreach ($data as $fieldName => $value) {
        $fieldRules = isset($rules[$fieldName]) ? $rules[$fieldName] : [];
        foreach ($fieldRules as $rule) {
            // $ruleErrors = call_user_func("Weblintreal\\FormValidator\\Functions\\validate" . ucfirst($rule), $fieldName, $value, $fieldRules, $messages);
            // $errors = array_merge($errors, $ruleErrors);
            if (strpos($rule, ':') !== false) {
                // Rule contains parameter, split into name and parameter parts
                list($ruleName, $ruleParam) = explode(':', $rule, 2);
                $ruleErrors = call_user_func("Weblintreal\\FormValidator\\Functions\\validate" . ucfirst($ruleName), $fieldName, $value, [$ruleName => $ruleParam], $messages);
            } else {
                // Rule does not contain parameter, call validation function normally
                $ruleErrors = call_user_func("Weblintreal\\FormValidator\\Functions\\validate" . ucfirst($rule), $fieldName, $value, $fieldRules, $messages);
            }
            $errors = array_merge($errors, $ruleErrors);
        }
    }
    return $errors;
}


/**
 * Validate required fields.
 * 
 * @param string $fieldName The name of the field to validate.
 * @param mixed $value The value of the field to validate.
 * @param array $rules The validation rules to apply.
 * @param array $messages The custom error messages for each rule.
 * @return array An array of error messages.
 */
function validateRequired(string $fieldName, $value, array $rules, array $messages): array
{
    $errors = [];
    if (in_array('required', $rules) && empty($value)) {
        $msg = isset($messages['required']) ? $messages['required'] : $fieldName . " is required.";
        $msg = str_replace(':attribute', $fieldName, $msg);
        $errors[$fieldName] = $msg;
    }
    return $errors;
}


/**
 * Validate email format.
 *
 * @param string $fieldName The name of the field to validate.
 * @param mixed $value The value of the field to validate.
 * @param array $rules The validation rules to apply.
 * @param array $messages The custom error messages for each rule.
 * @return array An array of error messages.
 */
function validateEmail(string $fieldName, $value, array $rules, array $messages): array
{
    $errors = [];
    if (in_array('email', $rules) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
        $msg = isset($messages['email']) ? $messages['email'] : 'Invalid email format.';
        $msg = str_replace(':attribute', $fieldName, $msg);
        $errors[$fieldName] = $msg;
    }
    return $errors;
}

/**
 * Validate minimum length.
 *
 * @param string $fieldName The name of the field to validate.
 * @param mixed $value The value of the field to validate.
 * @param array $rules The validation rules to apply.
 * @param array $messages The custom error messages for each rule.
 * @return array An array of error messages.
 */
function validateMin(string $fieldName, $value, array $rules, array $messages): array
{
    $errors = [];
    if (preg_match('/min:(\d+)/', implode(':', $rules), $matches)) {
        $minLength = (int) $matches[1];
        if (strlen($value) < $minLength) {
            $msg = isset($messages['min']) ? $messages['min'] : $fieldName . " must have at least $minLength characters.";
            $msg = str_replace(':attribute', $fieldName, $msg);
            $msg = str_replace(':min', $minLength, $msg);
            $errors[$fieldName] = $msg;
        }
    }
    return $errors;
}

/**
 * Validate maximum length.
 *
 * @param string $fieldName The name of the field to validate.
 * @param mixed $value The value of the field to validate.
 * @param array $rules The validation rules to apply.
 * @param array $messages The custom error messages for each rule.
 * @return array An array of error messages.
 */
function validateMax(string $fieldName, $value, array $rules, array $messages): array
{
    $errors = [];
    if (preg_match('/max:(\d+)/', implode(':', $rules), $matches)) {
        $maxLength = (int) $matches[1];
        if (strlen($value) > $maxLength) {
            $msg = isset($messages['max']) ? $messages['max'] : $fieldName . " must be at most $maxLength characters long.";
            $msg = str_replace(':attribute', $fieldName, $msg);
            $msg = str_replace(':max', $maxLength, $msg);
            $errors[$fieldName] = $msg;
        }
    }
    return $errors;
}

/**
 * Validate exact length.
 *
 * @param string $fieldName The name of the field to validate.
 * @param mixed $value The value of the field to validate.
 * @param array $rules The validation rules to apply.
 * @param array $messages The custom error messages for each rule.
 * @return array An array of error messages.
 */
function validateExactLength(string $fieldName, $value, array $rules, array $messages): array
{
    $errors = [];
    if (preg_match('/exactLength:(\d+)/', implode(':', $rules), $matches)) {
        $exactLength = (int) $matches[1];
        if (strlen($value) != $exactLength) {
            $msg = isset($messages['exactLength']) ? $messages['exactLength'] : $fieldName . " must be exactly $exactLength characters long.";
            $msg = str_replace(':attribute', $fieldName, $msg);
            $msg = str_replace(':exactLength', $exactLength, $msg);
            $errors[$fieldName] = $msg;
        }
    }
    return $errors;
}


/**
 * Validate URL.
 * 
 * @param string $fieldName The name of the field to validate.
 * @param mixed $value The value of the field to validate.
 * @param array $rules The validation rules to apply.
 * @param array $messages The custom error messages for each rule.
 * @return array An array of error messages.
 */
function validateUrl(string $fieldName, $value, array $rules, array $messages): array
{
    $errors = [];

    if (in_array('url', $rules) && !filter_var($value, FILTER_VALIDATE_URL)) {
        $msg = isset($messages['url']) ? $messages['url'] : $fieldName . " must be a valid URL.";
        $msg = str_replace(':attribute', $fieldName, $msg);
        $errors[$fieldName] = $msg;
    }

    return $errors;
}


/**
 * Validate regular expression pattern for a field.
 *
 * @param string $fieldName The name of the field to validate.
 * @param mixed $value The value of the field to validate.
 * @param array $rules The validation rules to apply.
 * @param array $messages The custom error messages for each rule.
 * @return array An array of error messages.
 */
function validateRegex(string $fieldName, $value, array $rules, array $messages): array
{
    $errors = [];
    if (preg_match('/regex:\/(.*)\//', implode(':', $rules), $matches)) {
        $regex = $matches[1];
        if (!preg_match($regex, $value)) {
            $msg = isset($messages['regex']) ? $messages['regex'] : $fieldName . " format is invalid.";
            $msg = str_replace(':attribute', $fieldName, $msg);
            $msg = str_replace(':regex', $regex, $msg);
            $errors[$fieldName] = $msg;
        }
    }
    return $errors;
}
