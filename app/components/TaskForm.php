<?php


namespace App\Component;

class TaskForm extends \Nette\Application\UI\Control {

	protected $DB;
	protected $User;
	protected $Project;
	protected $TaskList;

	public function __construct(
		\Nette\Database\Context $DB,
		\App\Model\User $User,
		\App\Model\Project $Project,
		\App\Model\TaskList $TaskList
	) {
		parent::__construct();
		$this->DB       = $DB;
		$this->User     = $User;
		$this->Project  = $Project;
		$this->TaskList = $TaskList;
	}

	public function render() {
		$this->template->setFile(__DIR__ . '/../templates/controls/taskForm.latte');
		$this->template->render();
	}

	protected function createComponentForm() {

		$form   = new UI\Form;

		// $states = new StateList($this->DB);
		$users  = new UserList($this->DB);
		$lists  = new TaskListFactory($this->DB, $this->User, $this->Project, new \App\Model\ProjectFactory($this->DB, $this->User));
		// $tags   = new TagList($this->DB);

		\Tracy\Debugger::barDump($this->TaskList, 'tasklist!');

		$form->addText('ta_name', 'Název úkolu', 128)
			->addRule(UI\Form::FILLED, 'Vyplňte název úkolu')
			->addCondition(UI\Form::FILLED);

		$form->addTextArea('ta_description', 'Popis úkolu');

		$form->addText('ta_timeTo', 'Splnit do')
			->addRule(UI\Form::PATTERN, 'Špatný formát datumu', '[0-9]{2}\. [0-9]{2}\. [0-9]{4}');

		$form->addCheckboxList('ta_users', 'Uživatelé', $users->getAll());

		$form->addCheckboxList('ta_taskLists', 'Seznamy', $l = $lists->getAllAsPairs($this->TaskList->tl_ID ?  false : true))
			->setRequired();


		$form->addCheckboxList('ta_tags', 'Tagy', array());

		$form->addHidden('ta_ID');
		$form->addHidden('ta_created');
		$form->addHidden('ta_taskListID', $this->TaskList->tl_ID);
		$form->addHidden('ta_projectID', $this->Project->pr_ID);
		$form->addSubmit('ta_send', 'Uložit');

		$TaskDetail = $this->TaskDetail;
		$User       = $User;

		$form->onSuccess[] = function() use ($form, $TaskDetail, $User) {

			$values = $form->getValues();

			foreach($values as $k => $v) {
				if (!in_array($k, array_keys($TaskDetail->data))) {
					if ($k == 'ta_users') {
						foreach($v as $user) {
							$TaskDetail->addUser($user);
						}
					}

					if ($k == 'ta_taskLists') {
						foreach($v as $list) {
							$TaskDetail->addList($list);
						}
					}

					continue;
				}

				$TaskDetail->set($k, $v);
			}

			$TaskDetail->set('ta_author', $User->getIdentity()->getId());

			$TaskDetail->addUser($User->getIdentity()->getId());
		};

		return $form;
	}


}
