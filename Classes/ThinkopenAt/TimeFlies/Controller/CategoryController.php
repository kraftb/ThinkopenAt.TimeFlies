<?php
namespace ThinkopenAt\TimeFlies\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "ThinkopenAt.TimeFlies". *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Controller\ActionController;
use ThinkopenAt\TimeFlies\Domain\Model\Category;

class CategoryController extends ActionController {

	/**
	 * @Flow\Inject
	 * @var \ThinkopenAt\TimeFlies\Domain\Repository\CategoryRepository
	 */
	protected $categoryRepository;

	/**
	 * @return void
	 */
	public function indexAction() {
		$this->view->assign('categories', $this->categoryRepository->findByParent(NULL));
	}

	/**
	 * @param \ThinkopenAt\TimeFlies\Domain\Model\Category $category
	 * @return void
	 */
	public function showAction(Category $category) {
		$this->view->assign('category', $category);
		$this->view->assign('subCategories', $this->categoryRepository->findByParent($category->getIdentifier()));
	}

	/**
	 * @return void
	 */
	public function newAction() {
		$this->view->assign('rootCategories', $this->categoryRepository->findByParent(NULL));
	}

	/**
	 * @param \ThinkopenAt\TimeFlies\Domain\Model\Category $newCategory
	 * @return void
	 */
	public function createAction(Category $newCategory) {
		$this->categoryRepository->add($newCategory);
		$this->addFlashMessage('Created a new category.');
		$this->redirect('index');
	}

	/**
	 * @param \ThinkopenAt\TimeFlies\Domain\Model\Category $category
	 * @return void
	 */
	public function editAction(Category $category) {
		$this->view->assign('category', $category);
		$this->view->assign('rootCategories', $this->categoryRepository->findByParent(NULL));
		$disabledCategoryIdentifiers = $this->categoryRepository->findAllIdentifiersRecursive($category);
		$this->view->assign('disabledCategoryIdentifiers', $disabledCategoryIdentifiers);
	}

	/**
	 * @param \ThinkopenAt\TimeFlies\Domain\Model\Category $category
	 * @return void
	 */
	public function updateAction(Category $category) {
		$this->categoryRepository->update($category);
		$this->addFlashMessage('Updated the category.');
		$this->redirect('index');
	}

	/**
	 * @param \ThinkopenAt\TimeFlies\Domain\Model\Category $category
	 * @return void
	 */
	public function deleteAction(Category $category) {
		$this->categoryRepository->remove($category);
		$this->addFlashMessage('Deleted a category.');
		$this->redirect('index');
	}

}
