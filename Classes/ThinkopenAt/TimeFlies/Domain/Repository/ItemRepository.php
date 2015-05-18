<?php
namespace ThinkopenAt\TimeFlies\Domain\Repository;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "ThinkopenAt.TimeFlies". *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Persistence\Repository;
use ThinkopenAt\TimeFlies\Domain\Dto\ReportConfiguration;
use ThinkopenAt\TimeFlies\Domain\Model\Category;

/**
 * @Flow\Scope("singleton")
 */
class ItemRepository extends Repository {

	/**
	 * @Flow\Inject
	 * @var \ThinkopenAt\TimeFlies\Domain\Repository\CategoryRepository
	 */
	protected $categoryRepository;

	/**
	 * Returns all items matching the criterias for the report
	 *
 	 * @param \ThinkopenAt\TimeFlies\Domain\Dto\ReportConfiguration $reportConfiguration
 	 * @param \ThinkopenAt\TimeFlies\Domain\Model\Category $category
	 * @return \TYPO3\Flow\Persistence\QueryResultInterface All items as requested by the report configuration
	 */
	public function findForReport(ReportConfiguration $reportConfiguration, Category $category = NULL) {
		$query = $this->createQuery();
		$constraints = array();

		$orderings = array(
			'category' => \TYPO3\Flow\Persistence\QueryInterface::ORDER_ASCENDING,
			'begin' => \TYPO3\Flow\Persistence\QueryInterface::ORDER_ASCENDING,
		);
		$query->setOrderings($orderings);

		// Set required categories
		if ($reportConfiguration->getIncludeSubcategories()) {
			$categoryIdentifiers = $this->categoryRepository->findAllIdentifiersRecursive($category);
		} else {
			$categoryIdentifiers = array($category->getIdentifier());
		}
		$constraints[] = $query->in('category', $categoryIdentifiers);

		// Set required comment
		switch ($reportConfiguration->getCommentOperator()) {
			case 'dont_care':
			break;

			case 'contains':
				$constraints[] = $query->contains('comment', $reportConfiguration->getComment());
			break;

			case 'not_contains':
				$constraints[] = $query->logicalNot($query->contains('comment', $reportConfiguration->getComment()));
			break;

			default:
				throw new \Exception('Invalid compare operator');
			break;
		}

		// Set required begin/end
		$constraints[] = $query->greaterThanOrEqual('begin', $reportConfiguration->getBegin());
		$constraints[] = $query->lessThanOrEqual('begin', $reportConfiguration->getEnd());

		$query->matching($query->logicalAnd($constraints));
		return $query->execute();
	}


	/**
	 * Returns all items having the specified category
	 *
 	 * @param \ThinkopenAt\TimeFlies\Domain\Model\Category $category
	 * @return \TYPO3\Flow\Persistence\QueryResultInterface All items as requested by the report configuration
	 */
	public function findByCategory(Category $category = NULL) {
		$orderings = array(
			'begin' => \TYPO3\Flow\Persistence\QueryInterface::ORDER_ASCENDING,
		);
		$this->setDefaultOrderings($orderings);
		return parent::findByCategory($category);
	}


}
