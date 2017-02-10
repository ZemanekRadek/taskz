<?php


namespace App\Component;

use Nette,
	Nette\Application\UI;

class TaskForm extends \Nette\Application\UI\Control {

	protected $DB;
	protected $User;
	protected $Project;
	protected $TaskList;
	protected $Task;

	public $onSave;

	public function __construct(
		\Nette\Database\Context $DB,
		\App\Model\User $User,
		\App\Model\Project $Project,
		\App\Model\TaskList $TaskList,
		\App\Model\Task $Task = null
	) {
		parent::__construct();
		$this->DB         = $DB;
		$this->User       = $User;
		$this->Project    = $Project;
		$this->TaskList   = $TaskList;
		$this->Task       = $Task;


		if (!$this->Task) {
			$this->Task = new \App\Model\Task($this->DB, $this->User, $this->Project, $this->TaskList);
		}
	}

	public function setProject(\App\Model\Project $Project) {
		$this->Project = $Project;
	}

	public function setTask(\App\Model\Task $Task) {
		$this->Task = $Task;
	}

	public function render() {
		$this->template->setFile(__DIR__ . '/../templates/controls/taskForm.latte');
		$this->template->render();
	}

	protected function createComponentForm() {

		$form   = new \Nette\Application\UI\Form;

		// $states = new StateList($this->DB);
		$users  = new \App\Model\UserList($this->DB);
		$lists  = new \App\Model\TaskListFactory($this->DB, $this->User, $this->Project, new \App\Model\ProjectFactory($this->DB, $this->User));
		// $tags   = new TagList($this->DB);
		$listsArray = array();

		foreach($lists->getAll() as $item) {
			$listsArray[] = array(
				'value'  => $item->tl_ID,
				'label'  => $item->tl_name,
				'color'  => $item->tl_color
			);
		}

		\Tracy\Debugger::barDump($listsArray, 'tasklist!');

		$form->addText('ta_name', 'Název úkolu', 128)
			->addRule(UI\Form::FILLED, 'Vyplňte název úkolu')
			->addCondition(UI\Form::FILLED);

		$form->addTextArea('ta_description', 'Popis úkolu');

		$form->addText('ta_timeTo', 'Splnit do')
			->addRule(UI\Form::PATTERN, 'Špatný formát datumu', '[0-9]{2}\. [0-9]{2}\. [0-9]{4}');

		$form->addCheckboxList('ta_users', 'Uživatelé', $users->getAll());

		$form->addText('ta_taskLists', 'Seznamy')
			->setAttribute('data-source', json_encode($listsArray));
		// $l = $lists->getAllAsPairs(false))
		//	->setRequired();


		$form->addCheckboxList('ta_tags', 'Tagy', array());

		$form->addHidden('ta_ID');
		$form->addHidden('ta_created');
		$form->addHidden('ta_taskListID', $this->TaskList->tl_ID);
		$form->addHidden('ta_projectID', $this->Project->pr_ID);
		$form->addSubmit('ta_send', 'Uložit');

		$User       = $this->User;
		$DB         = $this->DB;
		$Project    = $this->Project;
		$self       = $this;

		$form->onSuccess[] = function() use ($form, $User, $DB, $Project, $self) {

			$values = $form->getValues();
			$Task     = new \App\Model\Task($DB, $User, $Project, null, $values['ta_ID']);

			foreach($values as $k => $v) {

				if (!in_array($k, array_keys($Task->data))) {
					if ($k == 'ta_users') {
						foreach($v as $user) {
							$Task->addUser($user);
						}
					}

					if ($k == 'ta_taskLists') {
						foreach(explode(',', $v) as $list) {
							$list = trim($list);

							if (!is_numeric($list)) {
								// create list
								$taskList = new \App\Model\TaskList($DB, $User, $Project);
								$taskList->init(array(
									'tl_name'   => $list,
									'tl_author' => $User->getIdentity()->getId()
								));

								$taskList->addProject($values['ta_projectID']);

								$taskList->save();

								if (!$taskList->tl_ID) {
									continue;
								}

								$list = $taskList->tl_ID;
							}

							$Task->addList($list);
						}
					}

					continue;
				}

				$Task->set($k, $v);
			}

			$Task->set('ta_author', $User->getIdentity()->getId());

			$Task->addUser($User->getIdentity()->getId());

			if ($Task->save()) {
				$self->onSave($Task, $values);
			}
		};

		if ($this->Task && $this->Task->ID) {
			$form['ta_ID']->setValue($this->Task->ID);
			$form['ta_name']->setValue($this->Task->name);
			$form['ta_timeTo']->setValue($this->Task->timeTo->format('d. m. Y'));
			$form['ta_description']->setValue($this->Task->description);

			$list = array();
			foreach($this->Task->getTaskLists() as $item) {
				$list[] = $item->tasks_list_tl_ID;
			}
			$form['ta_taskLists']->setValue(implode(',', $list));

			$users = array();

			foreach($this->Task->getUsers() as $user) {
				$users[] = $user['us_ID'];
			}
			$form['ta_users']->setValue($users);

		}

		return $form;
	}


}
