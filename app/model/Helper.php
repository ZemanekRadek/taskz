<?php

namespace App\Model;


class Helper {

	const LIST_INBOX    = 'inbox';

	const LIST_URGENT   = 'urgent';

	const LIST_FINISHED = 'finished';

	public static $now = 0;

	public static $days = array(
		1 => 'Po',
		2 => 'Út',
		3 => 'St',
		4 => 'Čt',
		5 => 'Pá',
		6 => 'So',
		7 => 'Ne'
	);

	public static function formatUserName($user) {
		return substr($user->us_name, 0, 1) . substr($user->us_surname, 0, 1);
	}

	public static function listDay($list) {
		$return = array();

		self::$now = new \Nette\Utils\DateTime();

		$avarage = array();
		$total   = array();
		$current = array();
		foreach($list as $item) {
			$index = $item->timeTo ? (string) $item->timeTo : 0;

			if (!isset($return[$index])) {
				$return[$index] = array(
					'day'   => self::translateDate( (string) $item->timeTo),
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

		return $return;
	}

	public static function translateDate($date) {
		$time = \Nette\Utils\DateTime::from($date);

		$interval = $time->diff(self::$now);

		$y = $time->format('Y') != self::$now->format('Y') ? ' ' . $time->format('Y') : '';
		$d = $interval->format('%R%a');
		$n = self::$days[$time->format('N')] . ' ';

		if ($d > 2) {
			return $n . $time->format('d. m.') . $y;
		}

		if ($d == 0) {
			return 'Today';
		}

		if ($d == 1) {
			return 'Tomorrow';
		}
	}
}
