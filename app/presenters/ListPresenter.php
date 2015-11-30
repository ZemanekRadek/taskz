<?php
namespace App\Presenters;

use Nette,
	Nette\Application\UI,
	Tracy\Debugger as Debugger,
	App\Model;
	
class ListPresenter extends BasePresenter {
	
	
	/** @var \App\Model\Project */
	private $Project;
	
	/** @var \App\Model\Task */
	private $Task;
	
	/** @var \App\Model\TaskList */
	private $TaskListFactory;
	
	private $ProjectFactory;
	
	public $DB;
	
	private $User;
	
	private $TaskList;
	
	private $backlink;
	
	public function __construct(
		\App\Model\ProjectFactory $ProjectFactory, 
		\App\Model\TaskListFactory $TaskListFactory, 
		\App\Model\Language $lang,
		\App\Model\TaskList $TaskList,
		\App\Model\User $User,
		\Nette\Database\Context $DB
	) {
		parent::__construct($lang, $DB);
		
		$this->TaskListFactory = $TaskListFactory;
		$this->ProjectFactory  = $ProjectFactory;
		$this->DB              = $DB;
		$this->User            = $User;
		
		
	}
	
	public function startup() {
		parent::startup();
		
		$this->Project = new \App\Model\Project($this->DB, $this->User, $this->getParameter('projectID'));
		$this->TaskListFactory->setProject($this->Project);

		$this->TaskList = new \App\Model\TaskList($this->DB, $this->User, $this->Project, $this->getParameter('taskListID'));
		
		$this->template->TaskListFactory = $this->TaskListFactory;
		$this->template->ProjectFactory  = $this->ProjectFactory;
		$this->template->Project         = $this->Project;
		$this->template->TaskList        = $this->TaskList;
		
		$this->backlink = $this->storeRequest();
		
		Debugger::barDump($this->TaskList);
	}

	public function actionDefault() {
	}
	
	public function actionList() {
	}
	
	public function actionNew() {
	}
	
	public function createComponentNewList() {
		$List = new \App\Model\TaskList($this->DB, $this->User, $this->Project);
		return $List->getForm();
	}

	
	public function beforeRender() {
		$this->template->Project = $this->Project;
	}
	
	/*
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
	*/
}