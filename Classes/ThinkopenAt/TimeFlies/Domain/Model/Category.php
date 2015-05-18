<?php
namespace ThinkopenAt\TimeFlies\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "ThinkopenAt.TimeFlies". *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Flow\Entity
 * @Flow\Lazy
 */
class Category {

	/**
	 * @var \ThinkopenAt\TimeFlies\Domain\Model\Category
	 * @ORM\Column(nullable=true)
	 * @ORM\ManyToOne(inversedBy="children")
	 * @Flow\Lazy
	 */
	protected $parent;

	/**
	 * @var \Doctrine\Common\Collections\ArrayCollection<\ThinkopenAt\TimeFlies\Domain\Model\Category>
	 * @ORM\OneToMany(mappedBy="parent")
	 * @ORM\OrderBy({"name" = "ASC"})
	 */
	protected $children;

	/**
	 * @var string
	 * @Flow\Validate(type="Text")
	 * @Flow\Validate(type="StringLength", options={ "minimum"=1, "maximum"=80 })
	 * @ORM\Column(length=80)
	 */
	protected $name;
	
	/**
	 * @return string
	 */
	public function getIdentifier() {
		return $this->Persistence_Object_Identifier;
	}


	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return \ThinkopenAt\TimeFlies\Domain\Model\Category Returns itself for call chaining
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	/**
	 * @return \ThinkopenAt\TimeFlies\Domain\Model\Category
	 */
	public function getParent() {
		return $this->parent;
	}

	/**
	 * @param \ThinkopenAt\TimeFlies\Domain\Model\Category $parent
	 * @return \ThinkopenAt\TimeFlies\Domain\Model\Category Returns itself for call chaining
	 */
	public function setParent(\ThinkopenAt\TimeFlies\Domain\Model\Category $parent = NULL) {
		$this->parent = $parent;
		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection<\ThinkopenAt\TimeFlies\Domain\Model\Category>
	 */
	public function getChildren() {
		return $this->children;
	}

	/**
	 * @param \Doctrine\Common\Collections\ArrayCollection<\ThinkopenAt\TimeFlies\Domain\Model\Category> $children
	 * @return \ThinkopenAt\TimeFlies\Domain\Model\Category Returns itself for call chaining
	 */
	public function setChildren(\Doctrine\Common\Collections\ArrayCollection $children) {
		$this->children = $children;
		return $this;
	}

}
