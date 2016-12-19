<?php

namespace App\Model\Filters;

/**
* Flag filter
*
* @author zemanek.radek@gmail.com
*/
class Flag extends \Nette\Object {

	public function __invoke(\App\Model\Task $Task) {
		$container = \Nette\Utils\Html::el('span');
		$container->class[] = 'flags flags-container';

		$actualList = $Task->getTaskList();

		foreach($Task->getTaskLists() as $list) {


			if ($actualList &&  $actualList->tl_ID == $list->tasks_list->tl_ID) {
				continue;
			}

			$flag = \Nette\Utils\Html::el('span', $list->tasks_list->tl_name);
			$flag->class[] = 'flag flag-' . $list->tasks_list->tl_ico;
			$container->add(
				$flag
			);
		}

		return $container;
	}
}
