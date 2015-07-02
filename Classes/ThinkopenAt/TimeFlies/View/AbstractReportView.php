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

	/**
	 * The Content-Type HTTP header line which will be sent for reports of this type.
	 * Must get set in implementing classes.
	 *
	 * @var string
	 */
	protected $contentType = FALSE;

	/**
	 * Only variables whose name is contained in this array will be rendered
	 *
	 * @var array
	 */
	protected $variablesToRender = array('value');

	/**
	 * @var array
	 * @Flow\Inject(setting="Reports")
	 */
	protected $settings;

	/**
	 * Initializes the rendering. Should get called by all "render" methods extending this class.
	 *
	 * @return string The rendered view
	 */
	protected function initRender() {
		if (!$this->contentType) {
			throw new \Exception('No Content-Type set!');
		}
		$this->controllerContext->getResponse()->setHeader('Content-Type', $this->contentType);
	}

	/**
	 * Setter for injected settings.
	 *
	 * @param array $settings
	 * @return void
	 */
	public function setSettings(array $settings) {
		$this->settings = $settings;
	}

	/**
	 * Specifies which variables this CsvView should render
	 * By default only the variable 'value' will be rendered
	 *
	 * @param array $variablesToRender
	 * @return void
	 * @api
	 */
	public function setVariablesToRender(array $variablesToRender) {
		$this->variablesToRender = $variablesToRender;
	}



}

