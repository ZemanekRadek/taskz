<?php

namespace App\Model;


class Helper {

	const LIST_INBOX = 'inbox';

	const LIST_URGENT = 'urgent';


	public static function listDay($list) {
		$return = array();

		foreach($list as $item) {
			$index = $item->timeTo ? (string) $item->timeTo : 0;

			if (!isset($return[$index])) {
				$return[$index] = array(
					'day'   => (string) $item->timeTo,
					'items' => array()
				);
			}

			$return[$index]['items'][] = $item;
		}

		\Tracy\Debugger::barDump($return);
		return $return;
	}
}
