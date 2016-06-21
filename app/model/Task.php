<?php
namespace App\Model;

use Nette,
	Nette\Application\UI,
	Nette\Database\Context,
	Tracy\Debugger as Debugger;

class Task extends Nette\Object  {

	/** @var Nette\Database\Context @inject */
	private $DB;

	/** @var Nette\Security\User @inject */
	private $User;

	/** @var App\Model\Tasklistk @inject */
	private $TaskList;

	/** @var App\Model\Project @inject */
	private $Project;

	private $table = "tasks";

	private $tableUser = "tasks_user";

	private $tableList = "tasks_list_task";

	private $data  = array(
		'ta_ID'          => null,
		'ta_description' => null,
		'ta_author'      => null,
		'ta_timeTo'      => null,
		'ta_created'     => null,
		'ta_name'        => null
	);

	private $tags  = array();

	private $users = array();

	private $lists = array();

	/**
	 * @param Nette\Database\Connection $db
	 * @throws Nette\InvalidStateException
	 */
	public function __construct(
		\Nette\Database\Context $DB,
		\App\Model\User $User,
		\App\Model\Project $Project = null,
		\App\Model\TaskList $TaskList = null,
		$ID = null
	) {
		$this->DB      = $DB;
		$this->User    = $User;
		$this->Project = $Project;
		$this->TaskList = $TaskList;

		if ($ID) {
			/*
			$this->init($this->DB->table('tasks_list')->where(array(
				'tl_ID'      => $ID,
			))->fetch());
			*/
		}
	}

	public function getForm($actionURL = '') {

		$form   = new UI\Form;

		// $states = new StateList($this->DB);
		$users  = new UserList($this->DB);
		$lists  = new TaskListFactory($this->DB, $this->User, $this->Project);
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

		$form->onSuccess[] = function() use ($form) {

			$values = $form->getValues();

			foreach($values as $k => $v) {
				if (!in_array($k, array_keys($this->data))) {
					if ($k == 'ta_users') {
						foreach($v as $user) {
							$this->addUser($user);
						}
					}

					if ($k == 'ta_taskLists') {
						foreach($v as $list) {
							$this->addList($list);
						}
					}

					continue;
				}

				$this->data[$k] = $v;
			}




			$this->data['ta_author'] = $this->User->getIdentity()->getId();

			$this->addUser($this->data['ta_author']);
		};

		return $form;
	}

	public function addUser($ID) {
		$this->users[$ID] = array('us_ID' => $ID);
		return $this;
	}

	public function addList($ID) {
		$this->lists[$ID] = array('tl_ID' => $ID);
		return $this;
	}

	public function save() {

		$values = $this->data;
		$values['ta_timeTo'] = date('Y-m-d H:i:s', strtotime(str_replace(' ', '', $values['ta_timeTo'])));

		if ($values['ta_ID'] > 0) {
			unset($values['ta_created']);
			$this->DB
				->table($this->table)
					->where('ta_ID', $values['ta_ID'])
					->update($values);

		}
		else {
			unset($values['ta_ID']);
			$values['ta_created'] = date('Y-m-d H:i:s');

			$row = $this->DB
				->table($this->table)
				->insert($values);

			if (!$this->data['ta_ID'] = $row['ta_ID']) {
				throw new \Nette\InvalidArgumentException('Invalid keys for save');
			}

			// automaticky zaradit do inboxu
		}

		// uzivatele
		{
			$this->DB->table($this->tableUser)->where('tasks_ta_ID', $this->data['ta_ID'])->delete();

			foreach($this->users as $user) {
				$row = $this->DB->table($this->tableUser)->insert(array(
					'tasks_ta_ID' => $this->data['ta_ID'],
					'users_us_ID' => $user['us_ID']
				));
			}
		}

		// Seznamy
		{
			$this->DB->table($this->tableList)->where('tasks_ta_ID', $this->data['ta_ID'])->delete();

			foreach($this->lists as $list) {
				$row = $this->DB->table($this->tableList)->insert(array(
					'tasks_ta_ID' => $this->data['ta_ID'],
					'tasks_list_tl_ID' => $list['tl_ID']
				));
			}
		}

		$this->data = $values;

		return true;
	}

}
