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


	/** @var \Nette\Datbase\ActiveRow */
	private $model;

	private $table     = "tasks";

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
		}
	}

	public function getTaskLists() {
		return $this->model->related('tasks_list_task');
		// return $this->lists;
	}

	public function isFinished() {
		return $this->model->related('tasks_list_task')->where('tasks_list.tl_systemIdentifier = ? ', \App\Model\Helper::LIST_FINISHED)->count() > 0;
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
