<?php
namespace ThinkopenAt\TimeFlies\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "ThinkopenAt.TimeFlies". *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Controller\ActionController;
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
  	 * @var string
    */
	protected $viewFormatToObjectNameMap = array(
		'html' => 'TYPO3\Fluid\View\TemplateView',
		'csv' => 'ThinkopenAt\TimeFlies\View\CsvView'
	);

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
	 * @return void
	 */
	public function configureReportAction(Category $category = NULL) {
		$this->view->assign('category', $category);
		$this->view->assign('now', new \DateTime());
	}

	/**
	 * @param \ThinkopenAt\TimeFlies\Domain\Dto\ReportConfiguration $reportConfiguration
	 * @param \ThinkopenAt\TimeFlies\Domain\Model\Category $category
	 * @return void
	 */
	public function generateReportAction(ReportConfiguration $reportConfiguration, Category $category = NULL) {
		$reportConfiguration->setBeginFromParts();
		$reportConfiguration->setEndFromParts();
		$items = $this->itemRepository->findForReport($reportConfiguration, $category);

		$this->view->assign('value', $items);
		$this->view->setConfiguration(array(
			'mapping' => array(
				'value' => array(
					'begin' => array(
						'name' => 'begin',
					),
					'end' => array(
						'name' => 'end',
					),
					'duration' => array(
						'name' => 'duration',
					),
					'category' => array(
						'name' => 'category',
						'subProperty' => array(
							'name' => 'name',
						),
					),
					'comment' => array(
						'name' => 'comment',
					),
				),
			),
			'outputHeaders' => TRUE,
		));
	}

}

