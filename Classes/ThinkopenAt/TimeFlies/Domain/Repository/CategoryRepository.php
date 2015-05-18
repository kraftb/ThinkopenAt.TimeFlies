<?php
namespace ThinkopenAt\TimeFlies\Domain\Repository;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "ThinkopenAt.TimeFlies". *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Persistence\Repository;

/**
 * @Flow\Scope("singleton")
 */
class CategoryRepository extends Repository {

	// add customized methods here

	/**
	 * Returns all categories except the passed category or any of its children.
	 *
 	 * @param \ThinkopenAt\TimeFlies\Domain\Model\Category $exclude
	 * @return \TYPO3\Flow\Persistence\QueryResultInterface All categories except the excluded ones
	 */
	public function findAllExceptThose(\ThinkopenAt\TimeFlies\Domain\Model\Category $exclude) {
		$excludeThose = $this->findAllIdentifiersRecursive($exclude);
		$query = $this->createQuery();
		$query->matching($query->logicalNot($query->in('Persistence_Object_Identifier', $excludeThose)));
		return $query->execute();
	}

	/**
	 * Returns the passed category and all its children
	 *
 	 * @param \ThinkopenAt\TimeFlies\Domain\Model\Category $category
	 * @return array<string> Object identifiers
	 */
	public function findAllIdentifiersRecursive(\ThinkopenAt\TimeFlies\Domain\Model\Category $category = NULL) {
		if ($category === NULL) {
			return $this->findAllIdentifiers();
		}
		$result = array($category->getIdentifier());
		foreach ($category->getChildren() as $child) {
			$result = array_merge($result, $this->findAllIdentifiersRecursive($child));
		}
		return $result;
	}

	/**
	 * Returns the IDENTIFIERS of ALL categories
	 *
	 * @return array<string> Object identifiers
	 */
	public function findAllIdentifiers() {
		$categories = $this->findAll();
		$result = array();
		foreach ($categories as $category) {
			$result[] = $category->getIdentifier();
		}
		return $result;
	}

}

