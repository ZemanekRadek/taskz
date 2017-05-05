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
	public $Project;


	/** @var \Nette\Datbase\ActiveRow */
	private $model;

	private $table     = "tasks";

	private $tableUser = "tasks_user";

	private $tableList = "tasks_list_task";

	private $tableTag  = "tasks_tags";

	public $data  = array(
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

	public $onSuccess = array();

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
		$this->DB             = $DB;
		$this->User           = $User;
		$this->Project        = $Project;
		$this->TaskList       = $TaskList;

		if ($ID) {
			$this->load($ID);
		}
	}

	public function getTaskList() {
		return $this->TaskList;
	}


	public function & __get($name) {
		if (in_array('ta_' . $name, $s = array_keys($this->data))) {
			return $this->data['ta_' . $name];
		}

		return parent::__get($name);
	}

	public function set($name, $value) {
		$this->data[$name] = $value;
	}

	private function load($ID) {
		if (!$this->model) {
			$this->model = $this->DB->table($this->table)->get($ID);
		}

		$this->data = (array) $this->model->toArray();
	}

	public function finish() {
		if (!$this->isFinished()) {
			$list = $this->DB->table('tasks_list_user')->where('users_us_ID = ? AND tasks_list.tl_systemIdentifier = ? ', $this->User->getIdentity()->getId(), \App\Model\Helper::LIST_FINISHED)->fetch();
			if ($list->tl_ID) {
				$row = $this->DB->table($this->tableList)->insert(array(
					'tasks_ta_ID'      => $this->data['ta_ID'],
					'tasks_list_tl_ID' => $list->tl_ID
				));
			}

			// odstranit inbox
			$list = $this->DB->table('tasks_list_user')->where('users_us_ID = ? AND tasks_list.tl_systemIdentifier = ? ', $this->User->getIdentity()->getId(), \App\Model\Helper::LIST_INBOX)->fetch();
			if ($list->tl_ID) {
				$this->DB->table($this->tableList)->where('tasks_ta_ID = ? AND tasks_list_tl_ID = ? ', array($this->data['ta_ID'], $list->tl_ID))->delete();
			}
		}

	}

	public function getTaskLists() {
		return $this->model ? $this->model->related('tasks_list_task') : array();
		// return $this->lists;
	}

	public function getTags() {
		return $this->model ? $this->model->related('tasks_tags') : array();
	}

	public function isFinished() {
		return $this->model ? $this->model->related('tasks_list_task')->where('tasks_list.tl_systemIdentifier = ? ', \App\Model\Helper::LIST_FINISHED)->count() > 0 : false;
	}

	public function addUser($ID) {
		$this->users[$ID] = array('us_ID' => $ID);
		return $this;
	}

	public function getUsers() {
		if (!$this->users && $this->data['ta_ID']) {
			$data = $this->DB->table($this->tableUser)->select('*')->where('tasks_ta_ID', $this->data['ta_ID']);

			foreach($data as $user) {
				\Tracy\Debugger::barDump($user);
				$this->users[$user->users_us_ID] = array(
					'us_ID'    => $user->users_us_ID,
					'us_email' => $user->users->us_email,
					'us_name'  => $user->users->us_name,
					'us_surname'  => $user->users->us_surname,

				);
			}

		}

		return $this->users;
	}

	public function addList($ID) {
		$this->lists[$ID] = array('tl_ID' => $ID);
		return $this;
	}

	public function addTag($ID) {
		$this->tags[$ID] = array('tg_ID' => $ID);
		return $this;
	}

	public function save() {

		$values = $this->data;

		if ($values['ta_timeTo']) {
			$values['ta_timeTo'] = date('Y-m-d H:i:s', strtotime(str_replace(' ', '', $values['ta_timeTo'])));
		}
		else {
			$values['ta_timeTo'] = null;
		}

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

		\Tracy\Debugger::barDump($this, 'task on save');

		// Tagy
		{
			$this->DB->table($this->tableTag)->where('tasks_ta_ID', $this->data['ta_ID'])->delete();

			foreach($this->tags as $tag) {
				$row = $this->DB->table($this->tableTag)->insert(array(
					'tasks_ta_ID' => $this->data['ta_ID'],
					'tags_tg_ID' => $tag['tg_ID']
				));
			}
		}

		$this->data = $values;

		$this->onSuccess();

		return true;
	}

}
