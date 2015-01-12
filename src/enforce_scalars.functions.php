<?php

/**
 * Enforce Scalar Datatypes
 *
 * This function allows enforceing Scalar datatypes. When called at the head of
 * a function or method and provided with 'func_get_args' will ensure that the
 * parameters passed are of the correct scalar type. Setting an index to NULL
 * allows not validating that index. Setting $allow_null to FALSE forces the
 * $values to be set if a scalar definition is set. Causes a Fatal Error by
 * default. Set $return to TRUE to simply validate the variables.
 *
 * @param Array $values The values to validate.
 * @param Array $scalars An associative array of Scalar types to validate in $values.
 * @param bool $allow_null Wetther or not to allow NULL values to pass through as valid (if scalar definition is set).
 * @param bool $return Wether to return (validate). Defaults to FALSE, or Fatal Error (same as Type Hinting).
 *
 * @return bool Wether all Scalar tests passed or not.
 *
 * @author Michael Mullligan <michael@bigroomstudios.com>
 */
function enforce_scalars(Array $values, Array $scalars, $allow_null = NULL, $return = NULL) {
	list($values, $scalars, $allow_null, $return) = fill_defaults(
		func_get_args(), array(2 => TRUE, 3 => FALSE));
	$error = FALSE;
	foreach($scalars as $key => $scalar_type) {
		$error_message = '';
		$scalar_error  = FALSE;
		if(is_null($scalar_type)) {
			continue;
		} else if(!is_string($scalar_type)) {
			trigger_error("Scalar Enforce Call-Error: Unexpected '".get_type($scalar_type)."', expected String or NULL.", E_USER_ERROR);
		} else {
			$scalar_type = strtolower($scalar_type);
			if((!array_key_exists($key, $values) || is_null($values[$key])) && (bool) $allow_null) {
				continue;
			}
			$value = array_key_exists($key, $values) ? $values[$key] : NULL;
			if($scalar_type == 'null') {
				if(!is_null($value)) {    $scalar_error = TRUE; }
			} else if($scalar_type == 'float' || $scalar_type == 'real') {
				if(!is_float($value)) {   $scalar_error = TRUE; }
			} else if($scalar_type == 'int') {
				if(!is_int($value)) {     $scalar_error = TRUE; }
			} else if($scalar_type == 'numeric') {
				if(!is_numeric($value)) { $scalar_error = TRUE; }
			} else if($scalar_type == 'string') {
				if(!is_string($value)) {  $scalar_error = TRUE; }
			} else if($scalar_type == 'bool') {
				if(!is_bool($value)) {    $scalar_error = TRUE; }
			} else if($scalar_type == 'object') {
				if(!is_object($value)) {  $scalar_error = TRUE; }
			} else {
				trigger_error("Scalar Enforce Call-Error: Unrecognized Scalar Type '$scalar_type'.", E_USER_ERROR);
			}
			if($scalar_error) {
				trigger_error("Scalar Enforcement Error: Expected scalar type '$scalar_type' for index $key, '".gettype($value)."' given.", ($return ? E_USER_WARNING : E_USER_ERROR));
			}
		}
		$error = $error || $scalar_error;
	}
	return !$error;
}