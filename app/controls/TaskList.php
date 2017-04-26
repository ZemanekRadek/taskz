<?php
namespace App\Component;

class TaskList extends \Nette\Application\UI\Control {

	/** @var \App\Model\TaskList **/
	public $List;

	/** @var \App\Model\Project **/
	public $Project;

	/** @var \Nette\Database\Context **/
	public $DB;

	/** @var \App\Model\User **/
	public $User;

	/** @var array */
	protected $tasks;

	public function handleTask($id, $projectId = null) {
		if ($projectId) {
			$this->Project->loadFromId($projectId);
		}
		if ($this->presenter->isAjax()) {
			$this->redrawControl('task');
		}
		else {
			// $this->presenter->redirect('Task:detail', array('id' => $id, 'projectID'=>$this->Project->pr_ID,'projectName'=>$this->Project->pr_name,'listID'=>$this->List ? $this->List->tl_ID : null,'listName'=>$this->List ? $this->List->tl_name : null));
		}
	}

	public function handletaskDone($id, $blockId, $projectId, $taskListId) {

		$Task = $this->presenter->TaskFactory->getById($this->getParameter('id'));
		$Task->finish();

		if ($taskListId) {
			$this->List = $this->presenter->TaskList;
			$this->List->load($taskListId);
			$this->List->setProject($this->presenter->ProjectFactory->get($projectId));
			$this->tasks = null;
		}
		else {
			$this->tasks = $this->presenter->TaskFactory->getAll()->getTasks();
			$this->List = null;
		}

		$this->redrawControl('taskList');

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

	public function setUser(\App\Model\User $User) {
		$this->User = $User;
	}

	public function getTasks() {
		return $this->tasks;
	}

	public function render() {
		$template = $this->template;
		$template->setFile(__DIR__ . '/../templates/controls/taskList.latte');
		$template->List    = $this->List;
		$template->tasks   = $this->tasks;
		$template->Project = $this->Project;
		$template->User    = $this->User;
		$template->render();
	}

	public function createComponentTaskDetail() {
		$Component = new TaskDetail();

		$Component->setTaskFactory($this->presenter->TaskFactory);

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
