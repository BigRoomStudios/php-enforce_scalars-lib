<?php

/**
 * Fill Defaults
 *
 * Applies Default values to a passed array. Works best at the head of functions
 * that have parameter declarations set to default to NULL. When used this way,
 * allows the developer to skip a parameter by passing NULL, without affecting
 * it's actual default. To not apply to an index in passed array, simply do not
 * set the default index for that parameter (use numeric indecies).
 *
 * @param Array $values The values to apply the defaults to.
 * @param Array $defaults The defaults to apply (default array indexes dictate application)
 *
 * @return Array $values with applied defaults.
 *
 * @author Michael Mullligan <michael@bigroomstudios.com>
 */
function fill_defaults(Array $values, Array $defaults) {
	foreach($defaults as $key => $default) {
		if(!isset($values[$key])) {
			$values[$key] = $default;
		}
	}
	return $values;
}