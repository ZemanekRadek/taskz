<?php
namespace App\Component;

class TaskList extends \Nette\Application\UI\Control {

	/** @var \App\Model\TaskList **/
	public $List;

	/** @var \App\Model\Project **/
	public $Project;


	/** @var array */
	protected $tasks;

	public function handleTask($id) {
		if ($this->presenter->isAjax()) {




			$this->redrawControl('task');
		 	\Tracy\Debugger::barDump('is ajax task in list');
		}
		else {
			\Tracy\Debugger::barDump('no ajax task in list');

			// $this->presenter->redirect('Task:detail', array('id' => $id, 'projectID'=>$this->Project->pr_ID,'projectName'=>$this->Project->pr_name,'listID'=>$this->List ? $this->List->tl_ID : null,'listName'=>$this->List ? $this->List->tl_name : null));
		}
	}

	public function setList(\App\Model\TaskList $List) {
		$this->List = $List;
	}

	public function setProject(\App\Model\Project $Project) {
		$this->Project =  $Project;
	}

	public function setTasks(array $list) {
		$this->tasks = $list;
	}

	public function render() {
		$template = $this->template;
		$template->setFile(__DIR__ . '/../templates/controls/taskList.latte');
		$template->List    = $this->List;
		$template->tasks   = $this->tasks;
		$template->Project = $this->Project;
		$template->render();
	}

	public function createComponentTaskDetail() {
		\Tracy\Debugger::barDump($this->getParameters(), 'create component');
		$Component = new TaskDetail();
		if ($this->Project) {
			$Component->setProject($this->Project);
		}
		if ($this->List) {
			$Component->setList($this->List);
		}
		if ($this->getParameter('id')) {
			$Component->setTask($this->presenter->TaskFactory->getById($this->getParameter('id')));
		}
		return $Component;
	}
}
