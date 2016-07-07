<?php
namespace App\Presenters;

use Nette,
	Nette\Application\UI,
	Tracy\Debugger as Debugger,
	App\Model;

class ProjectPresenter extends BasePresenter {


	/** @var \App\Model\Project */
	private $Project;

	private $TaskListFactory;

	private $ProjectFactory;

	/** @var \App\Model\TaskFactory @inject */
	private $TaskFactory;

	public function __construct(
		\App\Model\Language $lang,
		\Nette\Database\Context $DB,
		\App\Model\Project $Project,
		\App\Model\TaskListFactory $TaskListFactory,
		\App\Model\TaskFactory $TaskFactory,
		\App\Model\ProjectFactory $ProjectFactory
	) {
		parent::__construct($lang, $DB);

		$this->Project         = $Project;
		$this->TaskListFactory = $TaskListFactory;
		$this->TaskFactory     = $TaskFactory;
		$this->ProjectFactory  = $ProjectFactory;
	}

	public function startup() {
		parent::startup();

		$this->template->TaskListFactory = $this->TaskListFactory;
		$this->template->ProjectFactory  = $this->ProjectFactory;
		$this->template->Project         = $this->Project;
	}

	protected function createComponentProjectForm() {
		$form = $this->Project->getForm();
		$form->onSuccess = array(array($this, 'saveProject'));

		return $form;
	}

	public function saveProject($form, $values) {

		$values = $form->getValues();
		$values['pr_author'] = $this->user->getIdentity()->getId();
		$this->Project->init($values);

		if (!$this->Project->save()) {
			return false;
		}

		$this->redirect('List:default', array(
			'projectID'   => $this->Project->pr_ID,
			'projectName' => $this->Project->pr_path
		));


		return true;
	}


	public function createComponentTaskList() {
		return $this->TaskFactory->getAll();
	}
}
