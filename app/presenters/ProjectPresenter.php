<?php
namespace App\Presenters;

use Nette,
	Nette\Application\UI,
	App\Model;
	
class ProjectPresenter extends BasePresenter {
	
	
	/** @var \App\Model\Project */
	private $Project;
	
	
	public function __construct(\App\Model\Project $Project) {
		$this->Project = $Project;
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