<?php
namespace App\Presenters;

use Nette,
	Nette\Application\UI,
	App\Model;
	
class ListPresenter extends BasePresenter {
	
	
	/** @var \App\Model\Project */
	private $Project;
	
	/** @var \App\Model\Task */
	private $Task;
	
	/** @var \App\Model\TaskList */
	private $TaskListFactory;
	
	
	public function __construct(
		\App\Model\TaskListFactory $TaskListFactory, 
		\App\Model\Language $lang,
		\App\Model\Project $Project,
		\App\Model\TaskList $TaskList,
		\Nette\Database\Context $DB
	) {
		parent::__construct($lang, $DB);
		
		$this->TaskListFactory = $TaskListFactory;
	}
	
	public function startup() {
		parent::startup();
		
		$this->template->taskListFactory = $this->TaskListFactory;
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