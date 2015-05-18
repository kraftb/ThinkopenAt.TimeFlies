<?php
namespace ThinkopenAt\TimeFlies\Validation\Validator;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "ThinkopenAt.TimeFlies". *
 *                                                                        *
 *                                                                        */


/**
 * Validator for "commentOperator" values
 *
 * @api
 */
class CommentOperatorValidator extends \TYPO3\Flow\Validation\Validator\AbstractValidator {

	/**
	 * @var array
	 */
	protected $validOperators = array('dont_care', 'contains', 'not_contains');

	/**
	 * @var boolean
	 */
	protected $acceptsEmptyValues = FALSE;

	/**
	 * Checks if the given value is a valid commentOperator
	 *
	 * @param mixed $value The value that should be validated
	 * @return void
	 * @api
	 */
	protected function isValid($value) {
		if (!in_array($value, $this->validOperators)) {
			$this->addError('The given value is not a valid comment compare operator.', 1419263613, array($value));
		}
	}
}
