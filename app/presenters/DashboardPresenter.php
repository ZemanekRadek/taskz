<?php
namespace App\Presenters;

use Nette,
	App\Model;

class DashboardPresenter extends BasePresenter {

	private $TaskListFactory;

	private $ProjectFactory;

	private $Project = null;

	public function __construct(
		\App\Model\ProjectFactory $ProjectFactory,
		\App\Model\TaskListFactory $TaskListFactory,
		\App\Model\Language $lang,
		\Nette\Database\Context $DB
	) {
		parent::__construct($lang, $DB, null);

		$this->TaskListFactory = $TaskListFactory;
		$this->ProjectFactory  = $ProjectFactory;
	}

	public function startup() {
		parent::startup();

		$this->template->TaskListFactory = $this->TaskListFactory;
		$this->template->ProjectFactory  = $this->ProjectFactory;
		$this->template->Project         = null;
	}

	public function actionDefault() {
		/*
		$this->Project = new \App\Model\Project($this->DB, $this->User);
		$this->Project->loadDefault();
		$this->TaskListFactory->setProject($this->Project);
		*/
	}


	public function createComponentTaskList() {
		$Component = new \App\Components\ComponentTaskList($this->TaskListFactory);
		$Component->getTaksList();
		return $Component;
	}

}
