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

		$form         = new \Nette\Application\UI\Form;

		// $states = new StateList($this->DB);
		$users        = new \App\Model\UserList($this->DB);
		$lists        = new \App\Model\TaskListFactory($this->DB, $this->User, $this->Project, new \App\Model\ProjectFactory($this->DB, $this->User));
		$TagFactory   = new \App\Model\TagFactory($this->DB);
		// $tags   = new TagList($this->DB);
		$listsArray   = array();
		$defaultInbox = array();

		foreach($lists->getAll() as $item) {
			$listsArray[] = array(
				'value'  => $item->tl_ID,
				'label'  => $item->tl_name,
				'color'  => $item->tl_color
			);

			if ($item->tl_systemIdentifier == \App\Model\Helper::LIST_INBOX) {
				$defaultInbox[] = $item->tl_ID;
			}
		}

		$tagsArray = array();
		foreach($TagFactory->getAll() as $item) {
			$tagsArray[] = array(
				'value' => $item->tg_ID,
				'label' => $item->tg_name,
				'color' => $item->tg_color
			);
		}

		\Tracy\Debugger::barDump($listsArray, 'tasklist!');

		$form->addText('ta_name', 'Název úkolu', 128)
			->addRule(UI\Form::FILLED, 'Vyplňte název úkolu')
			->addCondition(UI\Form::FILLED);

		$form->addTextArea('ta_description', 'Popis úkolu');

		$form->addText('ta_timeTo', 'Splnit do');
			// ->setRequired(FALSE)
			// ->addRule(UI\Form::PATTERN, 'Špatný formát datumu', '[0-9]{2}\. [0-9]{2}\. [0-9]{4}');

		$form->addCheckboxList('ta_users', 'Uživatelé', $users->getAll());

		$form->addText('ta_taskLists', 'Seznamy')
			->setAttribute('data-source', json_encode($listsArray));
		// $l = $lists->getAllAsPairs(false))
		//	->setRequired();

		$form->addText('ta_tags', 'Tagy')
			->setAttribute('data-source', json_encode($tagsArray));

		// $form->addCheckboxList('ta_tags', 'Tagy', array());

		$form->addHidden('ta_ID');
		$form->addHidden('ta_created');
		$form->addHidden('ta_taskListID', $this->TaskList->tl_ID);
		$form->addHidden('ta_projectID',  $this->Project->pr_ID);
		$form->addSubmit('ta_send', 'Uložit');

		$form->getElementPrototype()->onsubmit('tinyMCE.triggerSave()');

		$User       = $this->User;
		$DB         = $this->DB;
		$Project    = $this->Project;
		$self       = $this;

		// on succces form
		{
			$form->onSuccess[] = function() use ($form, $User, $DB, $Project, $TagFactory, $defaultInbox, $self)  {

				$values = $form->getValues();
				$Task     = new \App\Model\Task($DB, $User, $Project, null, $values['ta_ID']);

				foreach($values as $k => $v) {

					if (!in_array($k, array_keys($Task->data))) {

						if ($k == 'ta_users' && $v) {
							foreach($v as $user) {
								$Task->addUser($user);
							}
						}

						if ($k == 'ta_tags' && $v) {
							foreach(explode(',', $v) as $tag) {
								$tag = trim($tag);

								if (!is_numeric($tag)) {

									if ($is = $TagFactory->getFromName($tag)) {
										$tag = $is->tg_ID;
									}
									else {

										$tagModel = new \App\Model\Tag($DB);
										$tagModel->init(array(
											'tg_name' => $tag
										));
										$tagModel->save();

										if (!$tagModel->tg_ID) {
											continue;
										}

										$tag = $tagModel->tg_ID;
									}
								}

								$Task->addTag($tag);
							}
						}

						if ($k == 'ta_taskLists' && $v) {
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

					$taskList = null;

					if ($values['ta_projectID']) {
						$Project = new \App\Model\Project($DB, $User, $values['ta_projectID']);
					}

					if ($values['ta_taskListID']) {
						$taskList = new \App\Model\TaskList($DB, $User, $Project, $values['ta_taskListID']);
					}
					else {
						// prvni tasklist pro redirect v presenteru, mozno vynutit inbox
						$_list = explode(',', $values['ta_taskLists']);
						if ($_list) {
							foreach($_list as $item) {
								$taskList = new \App\Model\TaskList($DB, $User, $Project, $item);
								break;
								/*
								if ($item->tl_systemIdentifier == \App\Model\Helper::LIST_INBOX) {
								}
								*/
							}
						}
					}

					\Tracy\Debugger::barDump($taskList, 'default list');
					\Tracy\Debugger::barDump($defaultInbox, 'default inbox');

					$self->onSave($Task, $values, $Project, $taskList);
				}
			};
		}

		// set value from detail task
		if ($this->Task && $this->Task->ID) {
			$form['ta_ID']->setValue($this->Task->ID);
			$form['ta_name']->setValue($this->Task->name);
			if ($this->Task->timeTo) {
				$form['ta_timeTo']->setValue($this->Task->timeTo->format('d. m. Y'));
			}
			$form['ta_description']->setValue($this->Task->description);

			$list = array();
			foreach($this->Task->getTaskLists() as $item) {
				$list[] = $item->tasks_list_tl_ID;
			}
			$form['ta_taskLists']->setValue(implode(',', $list));


			$list = array();
			foreach($this->Task->getTags() as $item) {
				$list[] = $item->tags_tg_ID;
			}
			$form['ta_tags']->setValue(implode(',', $list));

			$users = array();

			foreach($this->Task->getUsers() as $user) {
				$users[] = $user['us_ID'];
			}
			$form['ta_users']->setValue($users);

		}
		else {
			if ($defaultInbox) {
				$form['ta_taskLists']->setValue(implode(',', $defaultInbox));
			}
			$form['ta_users']->setValue(array($User->getIdentity()->getId()));
		}

		return $form;
	}


}
