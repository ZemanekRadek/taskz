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

	private $data  = array(
		'ta_ID'      => null,
		'ta_author'  => null,
		'ta_timeTo'  => null,
		'ta_created' => null
	);

	private $users = array();

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

	public function getForm() {

		$form   = new UI\Form;
		// $states = new StateList($this->DB);
		$users  = new UserList($this->DB);
		$lists  = new TaskListFactory($this->DB, $this->User, $this->Project);
		// $tags   = new TagList($this->DB);

		$form->addText('ta_name', 'Název úkolu', 128)
			->addRule(UI\Form::FILLED, 'Vyplňte název úkolu')
			->addCondition(UI\Form::FILLED);

		$form->addTextArea('ta_description', 'Popis úkolu');

		$form->addText('ta_timeTo', 'Splnit do')
			->addRule(UI\Form::PATTERN, 'Špatný formát datumu', '[0-9]{2}\.[0-9]{2}\.[0-9]{4}');

		$form->addCheckboxList('ta_users', 'Uživatelé', $users->getAll());

		$form->addCheckboxList('ta_taskLists', 'Seznamy', $lists->getAll());

		$form->addHidden('ta_ID');
		$form->addHidden('ta_created');
		$form->addSubmit('ta_send', 'Uložit');

		$form->onSuccess[] = function($values) {

			foreach($values as $k => $v) {
				if (!in_array($k, array_keys($this->data))) {
					if ($k == 'ta_users') {
						foreach($v as $user) {
							$this->addUser($user);
						}
					}

					continue;
				}

				$this->data[$k] = $v;
			}


			$this->data['ta_author'] = $this->User->getIdentity()->getId();

			return $this->save();
		};

		return $form;
	}

	public function addUser($ID) {
		$this->users[] = array('us_ID' => $ID);
		return $this;
	}

	public function save() {

		if ($this->data['ta_ID'] > 0) {

			$this->db
				->table($this->table)
					->where('ta_ID', $this->data['ta_ID'])
					->update($this->data);

		}
		else {
			unset($values['ta_ID']);
			$this->db
				->table($this->table)
				->insert($this->data);

			if (!$this->data['ta_ID'] = $this->db->getInsertId()) {
				throw Nette\InvalidArgumentException('Invalid keys for save');
			}
		}

		// uzivatele
		{
			$this->DB->table($this->tableUser)->where('tu_ta_ID', $this->data['ta_ID'])->delete();

			foreach($this->users as $user) {
				$row = $this->DB->table($this->tableUser)->insert(array(
					'tu_ta_ID' => $this->data['ta_ID'],
					'tu_us_ID' => $user['us_ID']
				));
			}
		}
		$this->data = $values;

		return true;
	}

}
