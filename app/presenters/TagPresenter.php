<?php
namespace App\Presenters;

use Nette,
	Nette\Application\UI,
	App\Model;
	
class TagPresenter extends BasePresenter {
	
	
	/** @var \App\Model\Project */
	private $Project;
	
	/** @var \App\Model\Tag */
	private $Tag;
	
	/** @var \App\Model\TaskList */
	private $TagList;
	
	
	public function __construct(
		\App\Model\Project $Project,
		\App\Model\Tag $Tag,
		\App\Model\TagList $TagList
	) {
		$this->Project = $Project;
		$this->Tag     = $Tag;
		$this->TagList = $TagList;
	} 

	protected function createComponentTagForm() {
		$form =  $this->Tag->getForm();
		$form->onSuccess[] = array($this, 'saveTag');
		return $form;
	}
	
	public function actionDefault() {
		$this->actionList();
	}
	
	public function actionList() {
		$this->template->add(
			'tags', $l = $this->TagList->getAll(true)
		);
	}
	
	public function actionEdit($editID) {
		$this->Tag->load($editID);
		return true;
	}
	
	public function actionDelete($deleteID) {
		if ($this->Tag->load($deleteID)->delete()) {
			$this->redirect('Tag:list');
		}
		return true;
	}

	public function saveTag($form, $values) {
		
		if (!$this->Tag->save($values)) {
			return false;
		}
		
		$this->redirect('Tag:list');
		
		return true;
	}
}