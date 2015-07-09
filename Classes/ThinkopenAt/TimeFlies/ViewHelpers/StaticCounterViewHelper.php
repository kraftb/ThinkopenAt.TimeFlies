<?php
namespace ThinkopenAt\TimeFlies\ViewHelpers;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "ThinkopenAt.TimeFlies". *
 *                                                                        *
 *                                                                        */

use TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\Fluid\Core\ViewHelper;

/**
 * This ViewHelper can get used as counter for loops and or other stuff
 * You can simply assign a value to a variable, increment the variable
 * or get its current value as return value.
 *
 * The variable is static to this view helper so you can use it across
 * different templates, views, etc.
 *
 * = Examples =
 *
 * <code title="Set variable to static value">
 * <flies:staticCounter variable="i" set="0" />
 * </code>
 * <output></output>
 *
 * <code title="Increment/Decrement variable">
 * <flies:staticCounter variable="i" increment="-2" />
 * </code>
 * <output></output>
 *
 * <code title="Get current value">
 * <flies:staticCounter variable="i" />
 * </code>
 * <output>-2</output>
 *
 * @api
 */
class StaticCounterViewHelper extends AbstractViewHelper {

	/**
	 * @var array
	 */
	protected static $variables = array();

	/**
	 * Set, increments or outputs the requested counter variable.
	 * When yet-not-set variable gets incremented/decremented it is assumed to be "0".
	 *
	 * @param string $variable: The name of the variable on which to act
	 * @return integer $set: An absolute value to which to set the variable
	 * @return integer $increment: A relative value by which to increment/decrement the specified variable
	 * @return string The value of the variable if requested. Else an empty string.
	 * @api
	 */
	public function render($variable, $set = NULL, $increment = NULL) {
		if ($set === NULL && $increment === NULL) {
			if (isset(self::$variables[$variable])) {
				return self::$variables[$variable];
			}
			return '';
		}
		if ($set !== NULL) {
			self::$variables[$variable] = intval($set);
		}
		if ($increment !== NULL) {
			if (!isset(self::$variables[$variable])) {
			self::$variables[$variable] = 0;
			}
			self::$variables[$variable] += intval($increment);
		}
		return '';
	}
}

