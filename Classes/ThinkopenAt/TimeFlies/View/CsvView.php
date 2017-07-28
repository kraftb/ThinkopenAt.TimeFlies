<?php
namespace ThinkopenAt\TimeFlies\View;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "ThinkopenAt.TimeFlies". *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * A CSV view
 *
 * @api
 */
class CsvView extends AbstractReportView implements ReportInterface {

	/**
	 * The CSV rendering configuration.
	 * This configuration is set in the Settings.yaml file. You can change any aspect in the
	 * Flow main Settings.yaml file.
	 *
	 * The key "ThinkopenAt.TimeFlies.Reports.General" contains mulitple associative values
	 * with "CsvFieldname: PropertyName" mapping.
	 *
	 * Each "mapping" subarray has a key which must be identifcal with the variables to render.
	 * By default only the variable "value" will get rendered. So the minimal configuration
	 * will be something like set in the Configuration/Settings.yaml file or shown below:
	 *
	 * The "General" settings get overlaid with the "Csv" settings.
	 *
	 * ThinkopenAt:
	 *   TimeFlies:
	 *     Reports:
	 *       General:
	 *         mapping:
	 *           value:
	 *		         CsvField1:
	 *               name: 'propertyA'
	 *             CsvField2:
	 *               name: 'propertyB'
	 *             CsvField3:
	 *               name: 'propertyC'
	 *               subProperty:
	 *                 name: 'subPropertyA'
	 *             CsvField4:
	 *               name: 'propertyD'
	 *               subProperty:
	 *                 name: 'propertyA'
	 *                 subProperty:
	 *                   name: 'subPropertyB'
	 *             CsvField5:
	 *               name: 'dateProperty'
	 *               format: 'dateFormat'
	 *
	 *         outputHeaders: true
	 *         customHeader: ''
	 *         valueSeparator: ','
	 *         lineSeparator: "\n" 
	 *         enclosureCharacter: '"'
	 *         escapeCharacter: '\'
	 *
	 *
	 * The method "getPropertyA", "getPropertyB" getters will get used to retrieve the values from
	 * the objects in "value" key in the shown settings.
	 *
	 * If the value of a field is not a plain string it can also resolve object hierarchies.
	 *
	 * If one of the retrieved properties is a \DateTime object the key "format" will get used to
	 * determine the format in which to render the \DateTime object. If none is defined the global
	 * $this->dateFormat setting will getused
	 *
	 * The key "outputHeaders" will output a CSV header line before printing the data.
	 *
	 * The key "customHeader" will get printed instead of a generated header if this configuration option is set.
	 *
	 * The key "valueSeparator" can get used to define the separator between values (default: ",")
	 *
	 * The key "lineSeparator" can get used to define the separator between lines (default: "\n")
	 *
	 * The key "enclosureCharacter" can get used in conjunction with "escapeCharacter" to define enclosure/escape
	 * characters which will get applied upon strings which contains any of the defined separators or characters.
	 * Defaults for enclosureCharacter is a double quote (") and a backslash (\) for escapeCharacter.
	 *
	 * @var array
	 */
	protected $configuration = array();

	/**
	 * @var array
	 */
	protected $csvContent = array();

	/**
	 * @var string
	 */
	protected $valueSeparator = ',';

	/**
	 * @var string
	 */
	protected $lineSeparator = "\n";

	/**
	 * @var string
	 */
	protected $enclosureCharacter = "\"";

	/**
	 * @var string
	 */
	protected $escapeCharacter = "\\";

	/**
	 * @var string
	 */
	protected $specialCharacters = "";

	/**
	 * @var string
	 */
	protected $dateFormat = "Y-m-d H:i";

	/**
	 * Transforms the value view variable to a plain text CSV string
	 *
	 * @return string The CSV converted variables
	 * @api
	 */
	public function render() {


		$this->setupCsvOptions();

		// Add custom header
		if (isset($this->configuration['customHeader'])) {
			$this->csvContent[] = $this->configuration['customHeader'];
		}

		// Render variables
		$this->renderArray();
		return implode($this->lineSeparator, $this->csvContent);
	}

	/**
	 * Sets up various configurable aspects of the generated CSV.
	 * All options are usually set from the Settings.yaml file
	 *
	 * @return void
	 */
	protected function setupCsvOptions() {
		// Setup of class properties from configuration settings
		if (isset($this->configuration['valueSeparator'])) {
			$this->valueSeparator = $this->configuration['valueSeparator'];
		}
		if (isset($this->configuration['lineSeparator'])) {
			$this->lineSeparator = $this->configuration['lineSeparator'];
		}
		if (isset($this->configuration['enclosureCharacter'])) {
			$this->enclosureCharacter = $this->configuration['enclosureCharacter'];
		}
		if (isset($this->configuration['escapeCharacter'])) {
			$this->escapeCharacter= $this->configuration['escapeCharacter'];
		}
		$this->specialCharacters = $this->lineSeparator . $this->valueSeparator . $this->enclosureCharacter . $this->escapeCharacter;
		if (isset($this->configuration['dateFormat'])) {
			$this->dateFormat = $this->configuration['dateFormat'];
		}
	}

	/**
	 * Renders all configured variables
	 *
	 * @return void
	 * @api
	 */
	protected function renderArray() {
		foreach ($this->variablesToRender as $variableName) {
			if (isset($this->variables[$variableName]) && isset($this->configuration['mapping'][$variableName])) {
				$this->renderVariable($this->variables[$variableName], $this->configuration['mapping'][$variableName]);
			}
		}
	}

	/**
	 * Renders a configured variable by iterating over it
	 *
	 * @param mixed $value The variable which to render
	 * @param array $mapping The mapping for the variable
	 * @return void
	 */
	protected function renderVariable($value, array $mapping) {
		if ($this->configuration['outputHeaders']) {
			$this->csvContent[] = $this->renderHeader($mapping);
		}
		foreach ($value as $object) {
			$this->csvContent[] = $this->renderObject($object, $mapping);
		}
	}

	/**
	 * Yields the header for the passed mapping
	 *
	 * @param array $mapping The mapping for the header
	 * @return string The composed header
	 */
	protected function renderHeader(array $mapping) {
		return implode($this->valueSeparator, array_keys($mapping));
	}	

	/**
	 * Yields the data for a passed object
	 *
	 * @param mixed $object The object which to render
	 * @param array $getters The mapping for this variable
	 * @return string The result data
	 */
	protected function renderObject($object, array $mapping) {
		$result = array();
		foreach ($mapping as $property) {
			$result[] = $this->getObjectProperty($object, $property);
		}
		return implode($this->valueSeparator, $result);
	}	

	/**
	 * Returns the requested property value of an object.
	 * If the result of the first call is another object and $names contains more than one
	 * value this method will call itself again so properties of sub-objects can get retrieved.
	 *
	 * @param mixed $object The object from which to retrieve a property
	 * @param array $property The name of the propery which to retrieve and possible sup-property/format configurations
	 * @return mixed The result data
	 */
	protected function getObjectProperty($object, array $property) {
		$value = \TYPO3\Flow\Reflection\ObjectAccess::getProperty($object, $property['name']);
		$doEscape = true;
		if ($value instanceof \DateTime) {
			$value = $value->format(isset($property['format']) ? $property['format'] : $this->dateFormat);
		} else {
			if (is_object($value) && isset($property['subProperty']) && is_array($property['subProperty'])) {
				$doEscape = false;
				$value = $this->getObjectProperty($value, $property['subProperty']);
			}
		}
		if ($doEscape && strpbrk($value, $this->specialCharacters) !== FALSE) {
			$value = $this->encloseValue($this->escapeValue($value));
		}
		return $value;
	}

	/**
	 * Escapes any ESCAPE_CHARACTER and ENCLOSURE_CHARACTER by replacing it with ESCAPE_CHAR.[ESCAPE|ENCLOSURE]_CHAR
	 *
	 * @param string $value The value which to escape
	 * @return string The escaped value
	 */
	protected function escapeValue($value) {
		$value = str_replace($this->escapeCharacter, $this->escapeCharacter.$this->escapeCharacter, $value);
//		$value = str_replace($this->enclosureCharacter, $this->escapeCharacter.$this->enclosureCharacter, $value);
		$value = str_replace($this->enclosureCharacter, $this->enclosureCharacter . $this->enclosureCharacter, $value);
		return $value;
	}

	/**
	 * Wraps the passed string in its enclosure
	 *
	 * @param string $value The value which to wrap
	 * @return string The wrapped value
	 */
	protected function encloseValue($value) {
		return $this->enclosureCharacter . $value . $this->enclosureCharacter;
	}

	/*
 	 * Returns the file extension which is used for CSV report files
 	 *
 	 * @return string The string "csv"
 	 */
	public function getFileExtension() {
		return 'csv';
	}

	/*
 	 * Returns the HTTP Content-Type which is used for CSV reports
 	 *
 	 * @return string The string "text/csv"
 	 */
	public function getContentType() {
		return 'text/csv';
	}

	/*
 	 * Returns the format key which is used for CSV reports
 	 *
 	 * @return string The string "csv"
 	 */
	public function getFormatKey() {
//		return 'ThinkopenAt.TimeFlies.Csv';
		return 'csv';
	}

	/*
 	 * Returns the name of the report generate class
 	 *
 	 * @return string The name of the report class
 	 */
	public function getName() {
		return 'CSV Report';
	}

	/*
 	 * Returns a description for the report generate class
 	 *
 	 * @return string A textual description of the report type
 	 */
	public function getDescription() {
		return 'A simple CSV report with start, stop and duration fields, category and comment. Elements are grouped by category and sorted by begin time.';
	}

}
