<?php
namespace ThinkopenAt\TimeFlies\ViewHelpers\Format;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "ThinkopenAt.TimeFlies". *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * This view helper renders the day offset between the two passed dates.
 *
 * @api
 */
class DayOffsetViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractLocaleAwareViewHelper {

	/**
	 * Render the difference in days between the two passed DateTime objects.
	 *
	 * @param \DateTime $begin: The begin date
	 * @param \DateTime $end : The end date
	 * @return string The day difference between both passed \DateTime objects
	 * @api
	 */
	public function render(\DateTime $begin , \DateTime $end) {
		$date1 = new \DateTime($begin->format('Y-m-d'));
		$date2 = new \DateTime($end->format('Y-m-d'));
		$diff = $date1->diff($date2);
		if ($diff->days > 0) {
			return '+'.$diff->days;
		} elseif ($diff->days < 0) {
			return '-'.$diff->days;
		} else {
			return '';
		}
	}

}

