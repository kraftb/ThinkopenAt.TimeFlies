<?php
namespace ThinkopenAt\TimeFlies\View;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "ThinkopenAt.TimeFlies". *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * A view for generating an open office spreadsheet.
 *
 * @api
 */
class OpenOfficeSpreadsheetView extends AbstractReportTemplateView implements ReportInterface {

	/**
	 * An instance of the OpenOfficeSpreadsheetHelper
	 *
	 * @Flow\Inject
	 * @var \ThinkopenAt\TimeFlies\Helper\OpenOfficeSpreadsheetHelper
	 */
	protected $odsHelper = NULL;

	/**
	 * Resolve the template path and filename for the given action. If $actionName
	 * is NULL, looks into the current request.
	 *
	 * Loads the ODS template and returns the prepared content.xml
	 *
	 * @param string $actionName Name of the action. If NULL, will be taken from request.
	 * @return string The content.xml prepared for processing by Fluid
	 * @throws Exception\InvalidTemplateResourceException
	 */
	protected function getTemplateSource($actionName = NULL) {
		$templatePathAndFilename = $this->getTemplatePathAndFilename($actionName);
		$this->odsHelper->openFile($templatePathAndFilename);
		return $this->odsHelper->getPreparedTemplate();
	}

	/**
	 * Calls the parent "render" method. Then puts the rendered content back into
	 * the content.xml, compresses the .ODS file and returns the contents of the
	 * ODS.
	 *
	 * @param string $actionName Passed on to parent::render
	 * @return string The OSD binary data
	 */
	public function render($actionName = NULL) {
		// Fluid does caching on templates. This makes sense as each tempalte is
		// compiled into a PHP script. But we need the odsHelper to load the ODS
		// file (zip archive). Then the XML data can get altered in this method
		// by the call to setContentXml()
		if (!$this->odsHelper->getCurrentFile()) {
			// Calling "getTemplateSource" will load the template.
			$this->getTemplateSource($actionName);
		}

		$result = parent::render($actionName);
		$this->odsHelper->setContentXml($result);
		return $this->odsHelper->getFileData();
	}

	/*
 	 * Returns the file extension which is used for OpenOffice spreadsheet report files
 	 *
 	 * @return string The string "ods"
 	 */
	public function getFileExtension() {
		return 'ods';
	}

	/*
 	 * Returns the HTTP Content-Type which is used for OpenOffice spreadsheet reports
 	 *
 	 * @return string The string "application/vnd.oasis.opendocument.spreadsheet"
 	 */
	public function getContentType() {
		return 'application/vnd.oasis.opendocument.spreadsheet';
	}

	/*
 	 * Returns the format key which is used for OpenOffice spreadsheet reports
 	 *
 	 * @return string The string "ods"
 	 */
	public function getFormatKey() {
//		return 'ThinkopenAt.TimeFlies.OpenOfficeSpreadsheet';
		return 'ods';
	}

	/*
 	 * Returns the name of the report generate class
 	 *
 	 * @return string The name of the report class
 	 */
	public function getName() {
		return 'OpenOffice/LibreOffice Spreadsheet';
	}

	/*
 	 * Returns a description for the report generate class
 	 *
 	 * @return string A textual description of the report type
 	 */
	public function getDescription() {
		return 'An office spreadshet report with start, stop and duration fields, category and comment. This report is based on an XML fluid template which will get packed into an OpenOffice/LibreOffice ".ods" file.';
	}

}
