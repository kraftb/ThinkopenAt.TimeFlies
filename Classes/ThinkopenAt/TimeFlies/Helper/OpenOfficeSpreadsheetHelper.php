<?php
namespace ThinkopenAt\TimeFlies\Helper;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "ThinkopenAt.TimeFlies". *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * A helper class for handling OpenOffice/LibreOffice spreadsheet files
 *
 * @api
 */
class OpenOfficeSpreadsheetHelper {

	/**
	 * An instance of the flow ResourceManager
	 *
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Resource\ResourceManager
	 */
	protected $resourceManager = NULL;

	/**
	 * The filename of the temporary file copy being processed.
	 *
	 * @var string
	 */
	protected $temporaryFileName = '';

	/**
	 * The currently opened file
	 *
	 * @var string
	 */
	protected $currentFile = '';

	/**
	 * The ZipArchive object representing the ODS file
	 *
	 * @var \ZipArchive
	 */
	protected $odsResource = NULL;

	/**
	 * Opens the specified file. If a file is opened currently it gets closed first.
	 *
	 * @param string $odsFile: The ODS file to open
	 * @return void
	 */
	public function openFile($odsFile) {
		if ($odsFile === $this->currentFile) {
			return;
		}
		if ($this->odsResource !== NULL) {
			$this->closeFile();
		}
		// Create a local copy of the template
		$resource = $this->resourceManager->importResource($odsFile);
		$this->temporaryFileName = $resource->createTemporaryLocalCopy();

		$this->odsResource = new \ZipArchive();
		if ($this->odsResource->open($this->temporaryFileName) !== true) {
			throw new \Exception('Couldn\'t open ODS file!');
		}
		$this->currentFile = $odsFile;
	}

	/**
	 * Returns the binary content of the currently opened/loaded ODS file.
	 * To store any changed which have been made the odsResource gets closed
	 * first, then the file is read and finally it is reopened again.
	 *
	 * @return string The binary data of the current ODS file with all changes.
	 */
	public function getFileData() {
		if (!$this->odsResource instanceof \ZipArchive) {
			throw new \Exception('No ODS file opened!');
		}
		$this->odsResource->close();
		$data = file_get_contents($this->temporaryFileName);
		if ($this->odsResource->open($this->temporaryFileName) !== true) {
			throw new \Exception('Couldn\'t reopen ODS file!');
		}
		return $data;
	}

	/**
	 * Returns the name of the currently opened file.
	 *
	 * @return string The name of the currently opened ODS file
	 */
	public function getCurrentFile() {
		return $this->currentFile;
	}

	/**
	 * Closes the currently opened file.
	 *
	 * @param string $odsFile: The ODS file to open
	 * @return void
	 */
	public function closeFile() {
		if ($this->odsResource !== NULL) {
			$this->odsResource->close();
		}
		$this->odsResource = NULL;
		$this->currentFile = '';
	}

	/**
	 * Retrieves the "content.xml" from the ODS file
	 *
	 * @return string The XML from "content.xml"
	 */
	public function getContentXml() {
		if (!$this->odsResource instanceof \ZipArchive) {
			throw new \Exception('No ODS file opened!');
		}
		return $this->odsResource->getFromName('content.xml');
	}

	/**
	 * Writes back the content of "content.xml"
	 *
	 * @param string $content: The content for "content.xml"
	 * @return void
	 */
	public function setContentXml($content) {
		$content = trim($content);
		if (!$this->odsResource instanceof \ZipArchive) {
			throw new \Exception('No ODS file opened!');
		}
		$content = $this->minifyXml($content); 
		return $this->odsResource->addFromString('content.xml', trim($content));
	}

	/**
	 * Removes all unnecessary whitespace from the passed XML string
	 *
	 * @param string $content: The XML which to minify
	 * @return string The minified XML
	 */
	protected function minifyXml($content) {
		$xml = new \DOMDocument();
		$xml->preserveWhiteSpace = false;
		$xml->loadXML($content);
		$xml->formatOutput = false;
		return $xml->saveXML();
	}

	/**
	 * Right now the "GenerateTemplate.ods" is a specialy modified ODS file.
	 * The ODS file got extracted (It is just a ZIP file) and then the content.xml
	 * file was modified by adding some fluid viewHelpers like <f:for>. Altough
	 * quite some view helpers and variable output has already been done directly
	 * from within LibreOffice the wrapper tags for repeating content couldn't
	 * get inserted that way.
	 *
	 * Before the current approach was implemented it was inteded to mark some regions
	 * of the spreadsheet by adding range markers (<table:named-range>). Then some
	 * custom properties should get inserted which contain wrap strings which get wrapped
	 * around those rows/columns.
	 *
	 * But there are issues with this primarily intended solutions:
	 * 1. It would be quite more complex to implement this in PHP
	 * 2. The advantage isn't that great as editing the viewHelpers around ranges from
	 *    within LibreOffice is quite clumsy. So just a .ODS has to get extracated, the
	 *    content.xml edited and the ODS re-compressed.
	 *
	 * So for now do not implement this.
	 * If we need some special preprocessing of the template later on it can get
	 * implemented here
	 *
	 * @return string The prepared/preprocessed template
	 */
	public function getPreparedTemplate() {
		return $this->getContentXml();

/*
		$xml = new \DOMDocument();
		$xml->loadXML($this->getContentXml());

		$rootNode = $xml->documentElement;
		$rootNamespace = $rootNode->namespaceURI;

		$tableNamespace = $rootNode->getAttribute('xmlns:table');

		$namedRanges = $xml->getElementsByTagNameNS($tableNamespace, 'named-range');
		foreach ($namedRanges as $namedRange) {
			$name = $namedRange->getAttributeNS($tableNamespace, 'name');
			$range = $namedRange->getAttributeNS($tableNamespace, 'cell-range-address');
			echo $name.' / ' . $range."\n";
		}
exit();

		foreach ($rootNode->attributes as $attribute) {
var_dump($attribute);
}
exit();
*/
	}

}

