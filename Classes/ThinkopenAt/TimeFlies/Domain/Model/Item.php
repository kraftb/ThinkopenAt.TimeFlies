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
class Item {

	/**
	 * @var \ThinkopenAt\TimeFlies\Domain\Model\Category
	 * @ORM\Column(nullable=true)
	 * @ORM\ManyToOne
	 * @Flow\Lazy
	 */
	protected $category;

	/**
	 * @var string
	 * @Flow\Validate(type="Text")
	 * @Flow\Validate(type="StringLength", options={ "minimum"=1, "maximum"=200 })
	 * @ORM\Column(length=200)
	 */
	protected $comment;

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
	 * @var string
	 * @Flow\Transient
	 */
	protected $endTime;

	/**
	 * @var \DateTime
	 */
	protected $end;

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
	 * @return \ThinkopenAt\TimeFlies\Domain\Model\Category
	 */
	public function getCategory() {
		return $this->category;
	}

	/**
	 * @param \ThinkopenAt\TimeFlies\Domain\Model\Category $parent
	 * @return \ThinkopenAt\TimeFlies\Domain\Model\Category Returns itself for call chaining
	 */
	public function setCategory(\ThinkopenAt\TimeFlies\Domain\Model\Category $category = NULL) {
		$this->category = $category;
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
	 * Sets "end" from beginDate/endTime
	 *
	 * @return \ThinkopenAt\TimeFlies\Domain\Model\Category Returns itself for call chaining
	 */
	public function setEndFromParts() {
		$endTime = trim($this->getEndTime());
		$pattern = '/(\s+[+\-])([1-9][0-9]*)$/';
		$offset = 0;
		if (preg_match($pattern, $endTime, $matches)) {
			$endTime = trim(preg_replace($pattern, '', $endTime));
			$sign = trim($matches[1]);
			if ($sign !== '+') {
				throw new \Exception('Negative day offsets not supported currently!');
			}
			$offset = (int)trim($matches[2]);
		}
		$this->end = \DateTime::createFromFormat('Y-m-d H:i', $this->getBeginDate().' '.$endTime);
		if ($offset) {
			$interval = new \DateInterval('P' . $offset . 'D');
			$this->end->add($interval);
		}
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

	/**
	 * @return integer
	 */
	public function getDuration() {
		$diff = $this->end->getTimestamp() - $this->begin->getTimestamp();
		$hours = intval($diff/3600);
		$diff -= $hours*3600;
		$minutes = intval($diff/60);
		$fraction = 0;
		if ($minutes) {
			$fraction = $minutes/60;
		}
		return sprintf('%.02f', $hours + $fraction);
	}

	/**
	 * @return boolean
	 */
	public function getSpansAcrossMidnight() {
		$begin = $this->begin->format('Y-m-d');
		$end = $this->end->format('Y-m-d');
		return strcmp($begin, $end) ? TRUE : FALSE;
	}

}

