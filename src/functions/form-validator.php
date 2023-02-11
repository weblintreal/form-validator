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
            $ruleErrors = call_user_func("Weblintreal\\FormValidator\\Functions\\validate" . ucfirst($rule), $fieldName, $value, $fieldRules, $messages);
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
        if (strlen($value) == $exactLength) {
            $msg = isset($messages['exactLength']) ? $messages['exactLength'] : $fieldName . " must be exactly $exactLength characters long.";
            $msg = str_replace(':attribute', $fieldName, $msg);
            $msg = str_replace(':exactLength', $exactLength, $msg);
            $errors[$fieldName] = $msg;
        }
    }
    return $errors;
}
