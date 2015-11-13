<?php
namespace App\Presenters;

use Nette,
	App\Model;
	
class DashboardPresenter extends BasePresenter {
	
	private $TaskListFactory;
	
	public function __construct(
		\App\Model\TaskListFactory $TaskListFactory, 
		\App\Model\Language $lang,
		\Nette\Database\Context $DB
	) {
		parent::__construct($lang, $DB, null);
		
		$this->TaskListFactory = $TaskListFactory;
	}
	
	public function startup() {
		parent::startup();
		
		$this->template->taskListFactory = $this->TaskListFactory;
	}
	
	
	public function createComponentTaskList() {
		$Component = new \App\Components\ComponentTaskList($this->TaskListFactory);
		$Component->getTaksList();
		return $Component;
	}

}