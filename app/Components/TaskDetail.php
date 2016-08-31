<?php
namespace App\Component;

class TaskDetail extends \Nette\Application\UI\Control {

	/** @var \App\Model\Task **/
	public $Task;

	/** @var \App\Model\TaskList **/
	public $List;

	/** @var \App\Model\Project **/
	public $Project;

	public function setList(\App\Model\TaskList $List) {
		$this->List = $List;
	}

	public function setProject(\App\Model\Project $Project) {
		$this->Project =  $Project;
	}

	public function setTask(\App\Model\Task $Task) {
		$this->Task = $Task;
	}

	public function render() {
		\Tracy\Debugger::barDump('reder detail task');
		$template = $this->template;
		$template->setFile(__DIR__ . '/../templates/controls/taskDetail.latte');
		$template->List    = $this->List;
		$template->Task    = $this->Task;
		$template->Project = $this->Project;
		$template->render();
	}
}
