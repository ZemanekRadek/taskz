<?php
namespace App\Presenters;

use Nette,
	Nette\Application\UI,
	App\Model;
	
class TaskPresenter extends BasePresenter {
	
	
	/** @var \App\Model\Project */
	private $Project;
	
	/** @var \App\Model\Task */
	private $Task;
	
	/** @var \App\Model\TaskList */
	private $TaskList;
	
	
	public function __construct(
		\App\Model\Project $Project,
		\App\Model\Task $Task,
		\App\Model\TaskList $TaskList
	) {
		$this->Project = $Project;
		$this->Task = $Task;
		$this->TaskList = $TaskList;
	} 

	protected function createComponentTaskForm() {
		return $this->Task->getForm();
	}

	public function saveTask($form, $values) {
		
		if (!$this->Task->save($values)) {
			return false;
		}
		
		$this->redirect('Task:list');
		
		return true;
	}
}