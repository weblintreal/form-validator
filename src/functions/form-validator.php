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
        $errors[$fieldName] = isset($messages['required']) ? $messages['required'] : $fieldName . " is required.";
    }
    return $errors;
}