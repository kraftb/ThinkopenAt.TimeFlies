<?php
namespace ThinkopenAt\TimeFlies\Domain\Dto;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "ThinkopenAt.TimeFlies". *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 */
class ReportConfiguration {

	/**
	 * @var \DateTime
	 */
	protected $begin;

	/**
	 * @var string
	 * @Flow\Transient
	 */
	protected $beginDate;

	/**
	 * @var string
	 * @Flow\Transient
	 */
	protected $beginTime;

	/**
	 * @var \DateTime
	 */
	protected $end;

	/**
	 * @var string
	 * @Flow\Transient
	 */
	protected $endDate;

	/**
	 * @var string
	 * @Flow\Transient
	 */
	protected $endTime;

	/**
	 * @var boolean
	 */
	protected $includeSubcategories = FALSE;

	/**
	 * @var string
	 * @Flow\Validate(type="ThinkopenAt.TimeFlies:CommentOperator")
	 */
	protected $commentOperator = 'dont_care';

	/**
	 * @var string
	 * @Flow\Validate(type="Text")
	 * @Flow\Validate(type="StringLength", options={ "minimum"=1, "maximum"=200 })
	 */
	protected $comment;


	/**
	 * @return string
	 */
	public function getComment() {
		return $this->comment;
	}

	/**
	 * @param string $comment
	 * @return \ThinkopenAt\TimeFlies\Domain\Model\Category Returns itself for call chaining
	 */
	public function setComment($comment) {
		$this->comment = $comment;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getIncludeSubcategories() {
		return $this->includeSubcategories;
	}

	/**
	 * @param string $commentOperator
	 * @return \ThinkopenAt\TimeFlies\Domain\Model\Category Returns itself for call chaining
	 * @Flow\Validate(argumentName="commentOperator", type="ThinkopenAt.TimeFlies:CommentOperator")
	 */
	public function setCommentOperator($commentOperator) {
		$this->commentOperator = $commentOperator;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getCommentOperator() {
		return $this->commentOperator;
	}

	/**
	 * @param boolean $includeSubcategories
	 * @return \ThinkopenAt\TimeFlies\Domain\Model\Category Returns itself for call chaining
	 */
	public function setIncludeSubcategories($includeSubcategories) {
		$this->includeSubcategories = $includeSubcategories;
		return $this;
	}

	/**
	 * @return \DateTime
	 */
	public function getBegin() {
		return $this->begin;
	}

	/**
	 * @param \DateTime $begin
	 * @return \ThinkopenAt\TimeFlies\Domain\Model\Category Returns itself for call chaining
	 */
	public function setBegin(\DateTime $begin) {
		$this->begin = $begin;
		return $this;
	}

	/**
	 * Sets "begin" from beginDate/beginTime
	 *
	 * @return \ThinkopenAt\TimeFlies\Domain\Model\Category Returns itself for call chaining
	 */
	public function setBeginFromParts() {
		$this->begin = \DateTime::createFromFormat('Y-m-d H:i', $this->getBeginDate().' '.$this->getBeginTime());
		return $this;
	}

	/**
	 * Sets "end" from endTime
	 *
	 * @return \ThinkopenAt\TimeFlies\Domain\Model\Category Returns itself for call chaining
	 */
	public function setEndFromParts() {
		$this->end = \DateTime::createFromFormat('Y-m-d H:i', $this->getEndDate().' '.$this->getEndTime());
		return $this;
	}

	/**
	 * @return \DateTime
	 */
	public function getEnd() {
		return $this->end;
	}

	/**
	 * @param \DateTime $end
	 * @return \ThinkopenAt\TimeFlies\Domain\Model\Category Returns itself for call chaining
	 */
	public function setEnd(\DateTime $end) {
		$this->end = $end;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getBeginDate() {
		return $this->beginDate;
	}

	/**
	 * @param string $beginDate
	 * @return \ThinkopenAt\TimeFlies\Domain\Model\Category Returns itself for call chaining
	 */
	public function setBeginDate($beginDate) {
		$this->beginDate = $beginDate;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getBeginTime() {
		return $this->beginTime;
	}

	/**
	 * @param string $beginTime
	 * @return \ThinkopenAt\TimeFlies\Domain\Model\Category Returns itself for call chaining
	 */
	public function setBeginTime($beginTime) {
		$this->beginTime = $beginTime;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getEndDate() {
		return $this->endDate;
	}

	/**
	 * @param string $endDate
	 * @return \ThinkopenAt\TimeFlies\Domain\Model\Category Returns itself for call chaining
	 */
	public function setEndDate($endDate) {
		$this->endDate = $endDate;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getEndTime() {
		return $this->endTime;
	}

	/**
	 * @param string $endTime
	 * @return \ThinkopenAt\TimeFlies\Domain\Model\Category Returns itself for call chaining
	 */
	public function setEndTime($endTime) {
		$this->endTime = $endTime;
		return $this;
	}

}

