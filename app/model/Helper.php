<?php

namespace App\Model;


class Helper {

	const LIST_INBOX    = 'inbox';

	const LIST_URGENT   = 'urgent';

	const LIST_FINISHED = 'finished';


	public static function listDay($list) {
		$return = array();

		$avarage = array();
		$total   = array();
		$current = array();
		foreach($list as $item) {
			$index = $item->timeTo ? (string) $item->timeTo : 0;

			if (!isset($return[$index])) {
				$return[$index] = array(
					'day'   => (string) $item->timeTo,
					'items' => array()
				);

				$total[$index]   = 0;
				$current[$index] = 0;
				$avarage[$index] = 0;
			}

			$total[$index]++;
			if ($item->isFinished()) {
				$current[$index]++;
			}

			$avarage[$index] = ($current[$index] / $total[$index]) * 100;

			$return[$index]['items'][] = $item;

			$return[$index]['finished'] = array(
				'avarage' => $avarage[$index],
				'total'   => $total[$index],
				'current' => $current[$index]
			);
		}

		\Tracy\Debugger::barDump($return);
		return $return;
	}
}
