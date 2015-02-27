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
 * @param mixed[] $values The values to validate.
 * @param mixed[] $scalars An associative array of Scalar types to validate in $values.
 * @param bool $allow_null (optional) Wetther or not to allow NULL values to pass through as valid (if scalar definition is set).
 * @param bool $return (optional) Wether to return (validate). Defaults to FALSE, or Fatal Error (same as Type Hinting).
 *
 * @return bool Wether all Scalar tests passed or not.
 *
 * @author Michael Mullligan <michael@bigroomstudios.com>
 */
function enforce_scalars(Array $values, Array $scalars, Array $options = NULL) {
	$options = fill_defaults((array) $options, array(
			'allow_null' => TRUE,
			'return' => FALSE,
			'soft_numeric' => FALSE
	));
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
			if((!array_key_exists($key, $values) || is_null($values[$key])) && (bool) $options['allow_null']) {
				continue;
			}
			$value = array_key_exists($key, $values) ? $values[$key] : NULL;
			switch($scalar_type) {
				case 'array':
					$scalar_error = !is_array($value);
					break;
				case 'bool':
				case 'boolean':
					$scalar_error = !is_bool($value);
					break;
				case 'float':
				case 'double':
				case 'real':
					if($options['soft_numeric'] && $value == (float) $value) {
						break;
					}
					$scalar_error = !is_float($value);
					break;
				case 'int':
				case 'integer':
					if($options['soft_numeric'] && $value == (int) $value) {
						break;
					}
					$scalar_error = !is_int($value);
					break;
				case 'unset':
				case 'null' :
					$scalar_error = !is_null($value);
					break;
				case 'object':
					$scalar_error = !is_object($value);
					break;
				case 'string':
					$scalar_error = !is_string($value);
					break;
				case 'scalar':
					$scalar_error = !is_scalar($value);
					break;
				case 'numeric':
					$scalar_error = !is_numeric($value);
					break;
				case 'callable':
					$scalar_error = !is_callable($value);
					break;
				case 'resource':
					$scalar_error = !is_resource($value);
					break;
				default:
					trigger_error("Scalar Enforce Call-Error: Unrecognized Scalar Type '$scalar_type'.", E_USER_ERROR);
			}
			
			if($scalar_error) {
				$callers = called_from();
				trigger_error("Scalar Enforcement Error: Expected scalar type '$scalar_type' for index $key, '".gettype($value)."' given. ({$callers['file']}:{$callers['line']})", ($options['return'] ? E_USER_WARNING : E_USER_ERROR));
			}
		}
		$error = $error || $scalar_error;
	}
	return (bool) !$error;
}
