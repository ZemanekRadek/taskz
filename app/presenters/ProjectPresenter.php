<?php
namespace App\Presenters;

use Nette,
	Nette\Application\UI,
	App\Model;
	
class ProjectPresenter extends BasePresenter {
	
	
	/** @var \App\Model\Project */
	private $Project;
	
	private $TaskListFactory;
	
	
	public function __construct(
		\App\Model\Language $lang,
		\Nette\Database\Context $DB,
		\App\Model\Project $Project,
		\App\Model\TaskListFactory $TaskListFactory
	) {
		parent::__construct($lang, $DB);
		
		$this->Project         = $Project;
		$this->TaskListFactory = $TaskListFactory;
	} 
	
	public function startup() {
		parent::startup();
		
		$this->template->taskListFactory = $this->TaskListFactory;
	}
	
	protected function createComponentProjectForm() {
		return $this->Project->getForm();
	}

	public function saveProject($form, $values) {
		
		if ($this->Project->existFromName($values->pr_name)) {
			$form->addError('Tento projekt již existuje');
			return false;
		}

		if (!$this->Project->save($values)) {
			return false;
		}
		
		$this->redirect('Dashboard:');
		
		return true;
	}
}