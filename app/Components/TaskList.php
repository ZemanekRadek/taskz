<?php
namespace App\Component;

class TaskList extends \Nette\Application\UI\Control {

	/** @var \App\Model\TaskList **/
	public $List;

	public function setList( \App\Model\TaskList $List) {
		$this->List = $List;
	}


	public function render() {
		$template = $this->template;
		$template->setFile(__DIR__ . '/../templates/controls/taskList.latte');
		$template->List = $this->List;
		$template->render();
	}
}
