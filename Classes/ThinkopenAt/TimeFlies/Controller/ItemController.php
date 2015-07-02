<?php
namespace ThinkopenAt\TimeFlies\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "ThinkopenAt.TimeFlies". *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Controller\ActionController;
use TYPO3\Flow\Utility\Arrays;

use ThinkopenAt\TimeFlies\Domain\Model\Category;
use ThinkopenAt\TimeFlies\Domain\Model\Item;
use ThinkopenAt\TimeFlies\Domain\Dto\ReportConfiguration;

class ItemController extends ActionController {

	/**
	 * @Flow\Inject
	 * @var \ThinkopenAt\TimeFlies\Domain\Repository\CategoryRepository
	 */
	protected $categoryRepository;

	/**
	 * @Flow\Inject
	 * @var \ThinkopenAt\TimeFlies\Domain\Repository\ItemRepository
	 */
	protected $itemRepository;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Cryptography\HashService
	 */
	protected $hashService;

	/**
	 * Will get initialized to the configured report views.
	 *
	 * @var array
	 */
	protected $reportViews;

	/**
	 * @var array
	 * @Flow\Inject(setting="Reports")
	 */
	protected $reportSettings;

	/**
	 * @param \ThinkopenAt\TimeFlies\Domain\Model\Category $category
	 * @return void
	 */
	public function indexAction(Category $category = NULL) {
		$this->view->assign('currentCategory', $category);
		$this->view->assign('subCategories', $this->categoryRepository->findByParent($category));
		$this->view->assign('items', $this->itemRepository->findByCategory($category));
	}

	/**
	 * @param \ThinkopenAt\TimeFlies\Domain\Model\Item $item
	 * @param \ThinkopenAt\TimeFlies\Domain\Model\Category $category
	 * @return void
	 */
	public function showAction(Item $item, Category $category = NULL) {
		$this->view->assign('item', $item);
		$this->view->assign('currentCategory', $category);
	}

	/**
	 * @return void
	 */
	public function newAction() {
		$this->view->assign('now', new \DateTime());
		$this->view->assign('rootCategories', $this->categoryRepository->findByParent(NULL));
	}


	protected function initializeCreateAction() {
		$newItems = $this->request->getArgument('newItems');
		unset($newItems['*']);
		$this->request->setArgument('newItems', $newItems);
		// Mapping configuration for "newItems"
//		$newItemsConfiguration = $this->arguments->getArgument('newItems')->getPropertyMappingConfiguration();
/*

		// Get the additional key for all extra lines
		// Key "0" will be allowed by default
		$newItemKeys = array_keys($this->request->getArgument('newItems'));
		unset($newItemKeys[0]);

		// Get trusted properties
		$trustedPropertiesToken = $this->request->getInternalArgument('__trustedProperties');
		if (!is_string($trustedPropertiesToken)) {
			throw new \Exception('There should be trusted properties!');
		}
		$serializedTrustedProperties = $this->hashService->validateAndStripHmac($trustedPropertiesToken);
		$trustedProperties = unserialize($serializedTrustedProperties);
		if (!is_array($trustedProperties['newItems'][0])) {
			throw new \Exception('Trusted properties not as expected!');
		}
		$newItemProperties = $trustedProperties['newItems'][0];

		// Allow each additional line-key and set "newItem" properties in each item sub configuration
		foreach ($newItemKeys as $newItemKey) {
			$newItemsConfiguration->allowProperties($newItemKey);

			$subItemConfiguration = $newItemsConfiguration->forProperty($newItemKey);
			foreach ($newItemProperties as $newItemProperty => $value) {
				$subItemConfiguration->allowProperties($newItemProperty);
			}
			$subItemConfiguration->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter', \TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED, TRUE);

		}
*/
// print_r($newItemsConfiguration);
// exit();
	}

	/**
	 * @param \Doctrine\Common\Collections\Collection<\ThinkopenAt\TimeFlies\Domain\Model\Item> $newItems
	 * @return void
	 */
	public function createAction(\Doctrine\Common\Collections\Collection $newItems) {
		$cnt = 0;
		foreach ($newItems as $key => $newItem) {
			if ($key === '*') {
				continue;
			}
			$newItem->setBeginFromParts();
			$newItem->setEndFromParts();
			$this->itemRepository->add($newItem);
			$cnt++;
		}
		$this->addFlashMessage('Created ' . $cnt . ' new items.');
		$this->redirect('index');
	}

	/**
	 * @param \ThinkopenAt\TimeFlies\Domain\Model\Item $item
	 * @return void
	 */
	public function editAction(Item $item) {
		$this->view->assign('item', $item);
		$this->view->assign('rootCategories', $this->categoryRepository->findByParent(NULL));
	}

	/**
	 * @param \ThinkopenAt\TimeFlies\Domain\Model\Item $item
	 * @return void
	 */
	public function updateAction(Item $item) {
		$item->setBeginFromParts();
		$item->setEndFromParts();
		$this->itemRepository->update($item);
		$this->addFlashMessage('Updated the item.');
		$this->redirect('index');
	}

	/**
	 * @param \ThinkopenAt\TimeFlies\Domain\Model\Item $item
	 * @param \ThinkopenAt\TimeFlies\Domain\Model\Category $category
	 * @return void
	 */
	public function deleteAction(Item $item, Category $category = NULL) {
		$this->itemRepository->remove($item);
		$this->addFlashMessage('Deleted a item.');
		if ($category) {
			$this->forward('index', NULL, NULL, array('category' => $category));
		} else {
			$this->forward('index');
		}
	}

	/**
	 * @param \ThinkopenAt\TimeFlies\Domain\Model\Category $category
	 * @param string $format: The currently selected format (if any)
	 * @return void
	 */
	public function configureReportAction(Category $category = NULL, $format = '') {
		$this->view->assign('category', $category);
		$this->view->assign('now', new \DateTime());
		$this->initializeReportFormats($format);
		$this->view->assign('possibleFormats', $this->reportViews);
	}

	/**
	 * Initializes the "reportViews" class property. This property contains a presentation
	 * of all views which can get used for generating a report. The property is retrieved
	 * by iterating over the reports configured via Settings.yaml
	 *
	 * Each view gets instanciated and will have to identify itself by giving the formatKey
	 * it handles, its name and a description about it.
	 *
	 * @param string $selectedFormat: The format/view which should get marked as selected one
	 * @return void
	 */
	protected function initializeReportFormats($selectedFormat = '') {
		$this->reportViews = array();
		$cnt = 0;
		foreach ($this->reportSettings as $reportKey => $reportSetting) {
			if ($reportKey === 'General') {
				continue;
			}
			if (!isset($reportSetting['viewClass'])) {
				// @todo: Define a convention for determining the name of a viewClass
				throw new \Exception('Each configured report type must have a viewClass specified!');
			}
			$class = $reportSetting['viewClass'];
			if (!class_exists($class)) {
				throw new \Exception('Report view class "' . $class . '" does not exist!!');
			}
			$viewInstance = $this->objectManager->get($class);
			if (!$viewInstance instanceof \ThinkopenAt\TimeFlies\View\ReportInterface) {
				throw new \Exception('Views being used for generating a report must implement the ReportInterface!');
			}
			$formatKey = $viewInstance->getFormatKey();
			$selected = false;
			if ($selectedFormat) {
				$selected = $formatKey === $selectedFormat ? true : false;
			} else {
				$selected = $cnt ? false : true;
			}
			$this->reportViews[$formatKey] = array(
				'class' => $class,
				'reportKey' => $reportKey,
				'name' => $viewInstance->getName(),
				'description' => $viewInstance->getDescription(),
				'selected' => $selected,
			);
			$cnt++;
		}
	}

	/**
	 * This method sets the ActionController property "viewFormatToObjectNameMap" to
	 * all configured report views.
	 *
	 * @return void
	 */
	protected function initializeViewFormatToObjectNameMap() {
		$this->viewFormatToObjectNameMap = array();
		foreach ($this->reportViews as $reportFormat => $reportView) {
			$this->viewFormatToObjectNameMap[$reportFormat] = $reportView['class'];
		}
	}

	/**
	 * Initializes the view before invoking an action method.
	 *
	 * Will call the "initializeReport" method if the view implements the ReportInterface
	 *
	 * @param \TYPO3\Flow\Mvc\View\ViewInterface $view The view to be initialized
	 * @return void
	 */
	protected function initializeView(\TYPO3\Flow\Mvc\View\ViewInterface $view) {
		if ($view instanceof \ThinkopenAt\TimeFlies\View\ReportInterface) {
			$format = $this->request->getFormat();
			$reportKey = $this->reportViews[$format]['reportKey'];
			$view->initializeReport($reportKey);
		}
	}

	/**
	 * Initializes the generateReport action by retrieving/setting the list of possible output formats.
	 *
	 * @return void
	 */
	public function initializeGenerateReportAction() {
		$this->initializeReportFormats();
		$this->initializeViewFormatToObjectNameMap();
	}

	/**
	 * @param \ThinkopenAt\TimeFlies\Domain\Dto\ReportConfiguration $reportConfiguration
	 * @param \ThinkopenAt\TimeFlies\Domain\Model\Category $category
	 * @return void
	 */
	public function generateReportAction(ReportConfiguration $reportConfiguration, Category $category = NULL) {
		if (!$this->view instanceof \ThinkopenAt\TimeFlies\View\ReportInterface) {
			throw new \Exception('No valid report format selected');
		}
		$reportConfiguration->setBeginFromParts();
		$reportConfiguration->setEndFromParts();
		$items = $this->itemRepository->findForReport($reportConfiguration, $category);

		$this->view->assign('value', $items);
		$this->view->assign('category', $category);
		$this->view->assign('reportConfiguration', $reportConfiguration);
	}

}

