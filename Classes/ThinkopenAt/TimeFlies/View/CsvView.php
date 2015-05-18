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
class CsvView extends \TYPO3\Flow\Mvc\View\AbstractView {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Reflection\ReflectionService
	 */
	protected $reflectionService;

	/**
	 * @var \TYPO3\Flow\Mvc\Controller\ControllerContext
	 */
	protected $controllerContext;

	/**
	 * Only variables whose name is contained in this array will be rendered
	 *
	 * @var array
	 */
	protected $variablesToRender = array('value');

	/**
	 * The CSV rendering configuration
	 * The subkey "mapping" contains mulitple associative array with "CsvFieldname => PropertyName" mapping.
	 * Each "mapping" subarray has a key which must be identifcal with the variables to render. By default only the
	 * variable "value" will get rendered. So the minimal configuration will be something like:
	 *
	 * array(
	 *	'value' => array(
	 *		'csvField1' => array(
	 *			'name' => 'propertyA',
	 *		),
	 *		'csvField2' => array(
	 *			'name' => 'propertyB',
	 *		),
	 *		'csvField3' => array(
	 *			'name' => 'propertyC',
	 *			'subProperty' => array(
	 *				'name' => 'subPropertyA'
	 *			)
	 *		),
	 *		'csvField4' => array(
	 *			'name' => 'propertyD',
	 *			'subProperty' => array(
	 *				'name' => 'propertyA',
	 *				'subProperty' => array(
	 *					'name' => 'subPropertyB'
	 *				)
	 *			)
	 *		),
	 *		'csvField5' => array(
	 *			'name' => 'dateProperty',
	 *			'format' => 'dateFormat'
	 *		),
	 *	)
	 * )
	 *
	 * The method "getPropertyA", "getPropertyB" getters will get used to retrieve the values from
	 * the objects in "value" key in $this->variables
	 *
	 * If the value of a csv field is not a plain string it can also resolve object hierarchies.
	 *
	 * If one of the retrieved properties ia a \DateTime object the key "format" will get used to
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
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\Inject
	 */
	protected $persistenceManager;

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

	/**
	 * @param array $configuration The rendering configuration for this CSV view
	 * @return void
	 */
	public function setConfiguration(array $configuration) {
		$this->configuration = $configuration;
	}

	/**
	 * Transforms the value view variable to a plain text CSV string
	 *
	 * @return string The CSV converted variables
	 * @api
	 */
	public function render() {
		$this->controllerContext->getResponse()->setHeader('Content-Type', 'text/csv');

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

		// Add custom header
		if (isset($this->configuration['customHeader'])) {
			$this->csvContent[] = $this->configuration['customHeader'];
		}

		// Render variables
		$this->renderArray();

		return implode($this->lineSeparator, $this->csvContent);
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
		if ($value instanceof \DateTime) {
			$value = $value->format(isset($property['format']) ? $property['format'] : $this->dateFormat);
		} else {
			if (is_object($value) && isset($property['subProperty']) && is_array($property['subProperty'])) {
				$value = $this->getObjectProperty($value, $property['subProperty']);
			}
		}
		if (strpbrk($value, $this->specialCharacters) !== FALSE) {
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

}
