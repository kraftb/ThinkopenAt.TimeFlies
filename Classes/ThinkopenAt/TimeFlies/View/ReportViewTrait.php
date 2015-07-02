<?php
namespace ThinkopenAt\TimeFlies\View;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "ThinkopenAt.TimeFlies". *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Utility\Arrays;

/**
 * An abstract view for reports which do not use any template.
 *
 * @api
 */
trait ReportViewTrait {

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
	 * The "General" configuration from $this->settings overlaid with the specific configuration
	 * for the view.
	 *
	 * @var array
	 */
	protected $configuration = array();

	/**
	 * Will get called by the constructor to initialize this view.
	 *
	 * @return void
	 */	
	public function initializeReport($reportKey) {
		$this->configuration = Arrays::arrayMergeRecursiveOverrule($this->settings['General'], $this->settings[$reportKey]);
		$this->setContentTypeHeader();
		$this->setContentDispositionHeader();
	}

	/**
	 * Sets the "Content-Type" header being sent to the browser.
	 *
	 * @return void
	 */
	protected function setContentTypeHeader() {
		$contentType = $this->getContentType();
		if (!$contentType) {
			throw new \Exception('No Content-Type set!');
		}
		$this->controllerContext->getResponse()->setHeader('Content-Type', $contentType);
	}

	/**
	 * Sets the "Content-Disposition" header being sent to the browser.
	 *
	 * @return void
	 */
	protected function setContentDispositionHeader() {
		$fileName = $this->generateReportFileName();
		$contentDisposition = 'attachment; filename="' . $fileName . '"';
		$this->controllerContext->getResponse()->setHeader('Content-Disposition', $contentDisposition);
	}

	/**
	 * Generates a filename for a generated file.
	 * The filename will get sent in the content disposition header.
	 * This is the filename which the browser suggests to save the file as.
	 *
	 * @return string The proposed filename
	 */
	protected function generateReportFileName() {
		$fileName = 'report_';
		$now = new \DateTime();
		$fileName .= $now->format('Y-m-d_H-i');
		$fileName .= '.' . $this->getFileExtension();
		return $fileName;
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

