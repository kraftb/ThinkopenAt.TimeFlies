<?php
namespace ThinkopenAt\TimeFlies\View;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "ThinkopenAt.TimeFlies". *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * An abstract view for reports which do not use any template.
 *
 * @api
 */
abstract class AbstractReportView extends \TYPO3\Flow\Mvc\View\AbstractView {

	use ReportViewTrait;

}

