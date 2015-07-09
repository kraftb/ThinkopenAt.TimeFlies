<?php
namespace ThinkopenAt\TimeFlies\View;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "ThinkopenAt.TimeFlies". *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * An abstract view for reports are based on a template.
 *
 * @api
 */
abstract class AbstractReportTemplateView extends \TYPO3\Fluid\View\TemplateView {

	use ReportViewTrait;

}

