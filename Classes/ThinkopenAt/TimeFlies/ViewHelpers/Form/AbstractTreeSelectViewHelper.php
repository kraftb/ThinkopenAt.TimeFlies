<?php
namespace ThinkopenAt\TimeFlies\ViewHelpers\Form;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "ThinkopenAt.TimeFlies". *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Fluid\ViewHelpers\Form\SelectViewHelper;

/**
 * This view helper generates a hierarchial <select> dropdown list for categories
 *
 * @api
 */
abstract class AbstractTreeSelectViewHelper extends SelectViewHelper {

	/**
	 * @var array
	 */
	protected $basePrefix = array();

	/**
	 * @var string
	 */
	protected $currentPrefix = '';

	/**
	 * @var string
	 */
	protected $lastPrefix = '&nbsp;&#9495&#9473&nbsp;';

	/**
	 * @var array
	 */
	protected $lastValue = array();


	/**
	 * Initialize arguments.
	 *
	 * @return void
	 * @api
	 */
	public function initializeArguments() {
		parent::initializeArguments();
		$this->registerArgument('disableOptions', 'array', 'Array whose values specify keys of the "options" argument which should get disabled.', FALSE);
	}


	/**
	 * Render one option tag
	 *
	 * @param string $value value attribute of the option tag (will be escaped)
	 * @param string $label content of the option tag (will be escaped)
	 * @return string the rendered option tag
	 */
	protected function renderOptionTag($value, $label) {	
		$output = '<option value="' . htmlspecialchars($value) . '"';
		if ($this->isSelected($value)) {
			$output .= ' selected="selected"';
		}
		if ($this->hasArgument('disableOptions')) {
			if (in_array($value, $this->arguments['disableOptions'])) {
				$output .= ' disabled="disabled"';
			}
		}

		$disabled = FALSE;
		if (is_array($label)) {
			$disabled = $label['disabled'];
			$label = $label['label'];
		}

		if ($disabled) {
			$output .= ' disabled="disabled"';
		}

		if ($this->hasArgument('translate')) {
			$label = $this->getTranslatedLabel($value, $label);
		}
		$output .= '>' . $this->getPrefix($value) . htmlspecialchars($label) . '</option>';

		if (!$value) {
			return $output;
		}
		unset($this->arguments['prependOptionLabel']);

		$output .= $this->alternateSubOptions($value);
		$output .= $this->renderSubOptions($this->getChilds($value));

		return $output;
	}
		
	protected function alternateSubOptions($value) {
		return '';
	}

	protected function getChilds($value) {
		return $this->elementRepository->findByParent($value);
	}

	protected function renderSubOptions($children) {
		$output = '';
		if (count($children)) {
			// Increase level
			if ($this->currentPrefix) {
				$this->basePrefix[] = '&nbsp;&#9475&nbsp;&nbsp;';
			}
			$saveCurrentPrefix = $this->currentPrefix;
			$this->currentPrefix = '&nbsp;&#9507&#9473&nbsp;';

			// Save arguments
			$saveOptions = $this->arguments['options'];

			// Set arguments
			$this->arguments['options'] = $children;

			// Render sub options
			$childOptions = $this->getOptions();
			end($childOptions);
			$this->lastValue[] = key($childOptions);
			$output .= $this->renderOptionTags($childOptions);
			array_pop($this->lastValue);

			// Restore arguments
			$this->arguments['options'] = $saveOptions;

			// Decrease level
			array_pop($this->basePrefix);
			$this->currentPrefix = $saveCurrentPrefix;
		}
		return $output;
	}

	protected function getPrefix($value) {
		$lastValue = end($this->lastValue);
		return implode('', $this->basePrefix) . (($this->currentPrefix && ($value === $lastValue)) ? $this->lastPrefix : $this->currentPrefix);
	}

}

