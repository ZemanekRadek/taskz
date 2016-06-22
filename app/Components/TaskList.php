<?php
namespace App\Component;

class TaskList extends \Nette\Application\UI\Control {

	/** @var \App\Model\TaskList **/
	public $List;

	/** @var array */
	protected $tasks;

	public function setList(\App\Model\TaskList $List) {
		$this->List = $List;
	}

	public function setTasks(array $list) {
		$this->tasks = $list;
	}

	public function render() {
		$template = $this->template;
		$template->setFile(__DIR__ . '/../templates/controls/taskList.latte');
		$template->List  = $this->List;
		$template->tasks = $this->tasks;
		$template->render();
	}
}
