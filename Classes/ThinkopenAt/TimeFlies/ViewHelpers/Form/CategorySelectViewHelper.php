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
class CategorySelectViewHelper extends AbstractCategorySelectViewHelper {

	/**
	 * @Flow\Inject
	 * @var \ThinkopenAt\TimeFlies\Domain\Repository\CategoryRepository
	 */
	protected $categoryRepository;

}

