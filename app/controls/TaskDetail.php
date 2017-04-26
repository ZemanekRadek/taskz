<?php
namespace App\Component;

class TaskDetail extends \Nette\Application\UI\Control {

	/** @var \App\Model\Task **/
	public $Task;

	/** @var \App\Model\TaskList **/
	public $List;

	/** @var \App\Model\Project **/
	public $Project;

	/** @var \App\Model\TaskFormFactory **/
	public $TaskFormFactory;

	public function handleresolve($ID) {

	}

	public function handleremove($ID) {

	}

	/*****************************/

	public function setList(\App\Model\TaskList $List) {
		$this->List = $List;
	}

	public function setProject(\App\Model\Project $Project) {
		$this->Project =  $Project;
	}

	public function setTask(\App\Model\Task $Task) {
		$this->Task = $Task;
	}

	public function setTaskFactory(\App\Model\TaskFactory $TaskFactory) {
		$this->TaskFactory = $TaskFactory;
	}

	public function render() {
		$template = $this->template;
		$template->setFile(__DIR__ . '/../templates/controls/taskDetail.latte');
		$template->List    = $this->List;
		$template->Task    = $this->Task;
		$template->Project = $this->Project;
		$template->render();
	}

	public function createComponentTaskForm() {
		$form = $this->TaskFactory->getForm();

		if ($this->Task) {
			$form->setTask($this->Task);
		}

		$form->onSave[] = function($Task, $values, $Project, $TaskList) {
			if ($TaskList) {
				$this->presenter->redirect('List:default', array('taskListID' => $TaskList->tl_ID, 'taskListName' => $TaskList->tl_name, 'projectID' => $Project->pr_ID, 'projectName' => $Project->pr_name));
			}

			if ($Project) {
				$this->presenter->redirect('List:default', array('projectID' => $Project->pr_ID, 'projectName' => $Project->pr_name));
			}
		};

		return $form;
	}
}
