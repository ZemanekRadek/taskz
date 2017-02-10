<?php
namespace App\Model;

use Nette,
	Nette\Database\Context,
	Nette\Application\UI,
	Tracy\Debugger as Debugger;

class Project extends Nette\Object  {

	/** @var Nette\Database\Context @inject */
	private $DB;

	private $User;

	private $table = "projects";

	private $tableUser = "projects_user";

	private $data  = array(
		'pr_ID'     => null,
		'pr_name'   => null,
		'pr_path'   => null,
		'pr_author' => null,
	);

	private $users = array();

	public static $actualProject = null;

	/**
	 * @param Nette\Database\Connection $db
	 * @throws Nette\InvalidStateException
	 */
	public function __construct(
		\Nette\Database\Context $db,
		\App\Model\User $User,
		$ID = null
	) {
		$this->DB   = $db;
		$this->User = $User;

		$this->loadFromId($ID ? $ID : self::$actualProject);
	}

	public function loadFromId($id) {
		if ($id) {
			$this->init($this->DB->table($this->table)->where('pr_ID', $id)->fetch());
		}
	}

	public function & __get($name) {
		if (in_array($name, array_keys($this->data))) {
			return $this->data[$name];
		}

		return parent::__get($name);
	}

	public function init($data) {
		$keys = array_keys($this->data);
		foreach($data as $k => $v) {
			if (!in_array($k, $keys)) {
				continue;
			}
			$this->data[$k] = $v;
		}

		$this->data['pr_path'] = \App\Tools::friendly_url($this->data['pr_name']);

		if ($this->data['pr_author']) {
			$this->addUser($this->data['pr_author']);
		}

		return $this->data;
	}

	public function addUser($ID) {
		$this->users[] = array('us_ID' => $ID);
	}

	public function getForm() {
		$form = new UI\Form;;
		$form->addText('pr_name', 'Jméno', 64)
			->addRule(UI\Form::FILLED, 'Vyplňte jméno projektu')
			->addCondition(UI\Form::FILLED);
		$form->addHidden('pr_ID');

		$form->addSubmit('send', 'Uložit');

		$form->onSuccess[] = function($values) {
			$this->data = $values->getValues();
			return $this->save();
		};

		return $form;
	}

	public function save() {

		$this->data['pr_path'] = \App\Tools::friendly_url($this->data['pr_name']);

		if ($this->data['pr_ID'] > 0) {

			$this->DB
				->table($this->table)
					->where('pr_ID', $this->data['pr_ID'])
					->update($this->data);

		}
		else {
			$this->data['pr_ID'] = null;

			$row = $this->DB
				->table($this->table)
				->insert($this->data);

			if (!$row['pr_ID']) {
				throw \Nette\InvalidArgumentException('Invalid keys for save');
			}

			$this->data['pr_ID'] = $row['pr_ID'];

			// systemove slozky
			foreach(\App\Model\TaskList::$system as $system) {

				$system['tl_author'] = $this->data['pr_author'];

				$List = new \App\Model\TaskList($this->DB, $this->User, $this);
				$List
					->init($system)
					->addProject($this->data['pr_ID'])
					->save();
			}

		}

		// uzivatele
		{
			$this->DB->table($this->tableUser)->where('projects_pr_ID', $this->data['pr_ID'])->delete();

			foreach($this->users as $user) {
				$row = $this->DB->table($this->tableUser)->insert(array(
					'projects_pr_ID' => $this->data['pr_ID'],
					'users_us_ID' => $user['us_ID']
				));
			}
		}



		return true;
	}

	public function existFromName($name) {
		$p = $this->DB
			->table($this->table)
				->where('pr_name', $name)->fetch();

		return isset($p->pr_ID) && $p->pr_ID;
	}

	public function delete(){
		if (!$this->data['pr_ID']) {
			return false;
		}

		if (!$this->data['pr_author'] != $this->User->getIdentity()->getId()) {
			return false;
		}

		return $this
			->DB
				->table($this->table)
					->where('pr_ID', $this->data['pr_ID'])
					->delete();
	}

	public function loadDefault() {
		// Debugger::barDump($this->User, 'userload default');
		// $this->DB->table($this->tableUser)->where('pu_us_ID')
		if ($row = $this->DB
			->table($this->tableUser)
				->where('users_us_ID', $this->User->getIdentity()->getId())
				->get(1)) {
			$this->init($row);
		}
		return $this;
	}


}
