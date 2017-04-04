<?php
namespace App\Presenters;

use Nette,
	Nette\Application\UI,
	Tracy\Debugger as Debugger,
	App\Model;

class TaskPresenter extends BasePresenter {


	/** @var \App\Model\Project */
	public $Project;

	/** @var \App\Model\Task */
	private $Task;

	/** @var \App\Model\TaskList */
	public $TaskList;

	/** @var \App\Model\TaskFactory */
	public $TaskFactory;

	public $DB;

	public $User;

	private $ProjectFactory;

	public $TaskListFactory;


	public function __construct(
		\App\Model\ProjectFactory $ProjectFactory,
		\App\Model\TaskListFactory $TaskListFactory,
		\App\Model\TaskFactory $TaskFactory,
		\App\Model\Language $lang,
		\App\Model\User $User,
		\App\Model\TaskList $TaskList,
		\App\Model\Project $Project,
		\App\Model\Task $Task,
		\Nette\Database\Context $DB

	) {
		parent::__construct($lang, $DB);

		$this->TaskListFactory = $TaskListFactory;
		$this->ProjectFactory  = $ProjectFactory;
		$this->TaskFactory     = $TaskFactory;
		$this->DB              = $DB;
		$this->User            = $User;
	}

	public function startup() {
		parent::startup();

		$this->Project = new \App\Model\Project($this->DB, $this->User, $this->getParameter('projectID'));
		$this->TaskListFactory->setProject($this->Project);
		$this->TaskFactory->setProject($this->Project);

		$this->TaskList = new \App\Model\TaskList($this->DB, $this->User, $this->Project, $this->getParameter('taskListID'));

		$this->template->TaskListFactory = $this->TaskListFactory;
		$this->template->ProjectFactory  = $this->ProjectFactory;
		$this->template->Project         = $this->Project;
		$this->template->TaskList        = $this->TaskList;

		// Debugger::barDump($this->TaskList);
	}

	public function actionNew(){
		$this->Task = new \App\Model\Task($this->DB, $this->User, $this->Project, $this->TaskList, null);
		$this->template->Task = $this->Task;
	}

	public function handleDetail() {
		$this->Task = new \App\Model\Task($this->DB, $this->User, $this->Project, $this->TaskList, null);

		if ($this->isAjax()) {
			$this->template->Task = $this->Task;
		}

	}

	public function actionDetail() {
		$this->Task = new \App\Model\Task($this->DB, $this->User, $this->Project, $this->TaskList, null);
		$this->template->Task = $this->Task;
	}

	protected function createComponentTaskForm() {
		// $this->Task = new \App\Model\Task($this->DB, $this->User, $this->Project, $this->TaskList, null);
		$Task      = $this->Task;
		$Presenter = $this;
		$Form      = $this->TaskFactory->getForm();

		if ($this->getParameter('id')) {
			$Form->setTaskDetail(
				$this->TaskFactory->getById($this->getParameter('id'))
			);
		}

		$Form->onSave[] = function($Task, $values, $Project, $TaskList) use ($Presenter) {

			if ($TaskList) {
				$this->presenter->redirect('List:default', array('taskListID' => $TaskList->tl_ID, 'taskListName' => $TaskList->tl_name, 'projectID' => $Project->pr_ID, 'projectName' => $Project->pr_name));
			}

			if ($Project) {
				$this->presenter->redirect('List:default', array('projectID' => $Project->pr_ID, 'projectName' => $Project->pr_name));
			}

		};

		return $Form;
	}

	public function saveTask($form, $values) {

		if (!$this->Task->save($values)) {
			return false;
		}

		$this->redirect('Task:list');

		return true;
	}
}
