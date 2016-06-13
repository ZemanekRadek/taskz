<?php
namespace App\Presenters;

use Nette,
	Nette\Application\UI,
	Tracy\Debugger as Debugger,
	App\Model;

class ListPresenter extends BasePresenter {


	/** @var \App\Model\Project @inject */
	private $Project;

	/** @var \App\Model\Task @inject */
	private $Task;

	/** @var \App\Model\TaskList @inject */
	private $TaskListFactory;

	/** @var \App\Model\ProjectFactory @inject */
	private $ProjectFactory;

	/** @var \App\Model\TaskFactory @inject */
	private $TaskFactory;

	public $DB;

	private $User;

	private $TaskList;

	private $backlink;

	public function __construct(
		\App\Model\ProjectFactory $ProjectFactory,
		\App\Model\TaskListFactory $TaskListFactory,
		\App\Model\TaskFactory $TaskFactory,
		\App\Model\Language $lang,
		\App\Model\TaskList $TaskList,
		\App\Model\User $User,
		\Nette\Database\Context $DB
	) {
		parent::__construct($lang, $DB);

		$this->TaskListFactory = $TaskListFactory;
		$this->TaskFactory     = $TaskFactory;
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

		// Debugger::barDump($this->TaskList);
	}

	public function actionDefault() {
		$this->template->presenter = $this;
	}

	public function actionList() {
	}

	public function actionNew() {
		$this->template->parameters = $this->getParameters();
		$presenter = $this;
		/*
		$this->TaskList->onSuccess[] = function() use ($presenter) {
			Debugger::barDump('??');
			// $presenter->redirect('');
		};
		*/
	}

	public function createComponentNewList() {
		$presenter = $this;

		$List = new \App\Model\TaskList($this->DB, $this->User, $this->Project);
		$List->addProject($presenter->getParameter('projectID'));

		$form = $List->getForm();
		$form['projectID']->setValue($presenter->getParameter('projectID'));
		$form['projectName']->setValue($presenter->getParameter('projectName'));

		// $form->setAction($this->link(':List:new', $this->getParameters()));

		$form->onSuccess[] = function($form) use ($presenter) {

			$values = $form->getValues();

			$presenter->redirect('List:default', array('projectID' => $values['projectID'], 'projectName' => $values['projectName']));

		};


		return $form;
	}

	public function createComponentTaskList() {
		return $this->TaskFactory->getFromList($this->TaskList);
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
