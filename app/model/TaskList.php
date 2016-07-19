<?php
namespace App\Model;

use Nette,
	Nette\Database\Context,
	Nette\Application\UI,
	Tracy\Debugger as Debugger;

class TaskList extends Nette\Object  {

	/** @var Nette\Database\Context @inject */
	private $DB;

	private $User;

	public $Project;

	private $table         = 'tasks_list';

	private $tableUser     = 'tasks_list_user';

	private $tableProjects = 'tasks_list_project';

	private $tableTasks    = 'tasks_list_task';

	// system list identifier
	public static $system = array(
		\App\Model\Helper::LIST_INBOX     => array('tl_name' => 'Inbox', 'tl_ico'    => 'icon-inbox', 'tl_systemIdentifier'                => \App\Model\Helper::LIST_INBOX, 'tl_path' => 'inbox', 'tl_color' => '0000ff'),
		\App\Model\Helper::LIST_URGENT    => array('tl_name' => 'Urgent', 'tl_ico'   => 'icon-assignment_late', 'tl_systemIdentifier'      => \App\Model\Helper::LIST_URGENT, 'tl_path' => 'urgent', 'tl_color' => 'ff0000'),
		\App\Model\Helper::LIST_FINISHED  => array('tl_name' => 'Finished', 'tl_ico' => 'icon-assignment_turned_in', 'tl_systemIdentifier' => \App\Model\Helper::LIST_FINISHED, 'tl_path' => 'finished', 'tl_color'=>'00ff00'),
		'deleted'   => array('tl_name' => 'Deleted', 'tl_ico'  => 'icon-broken_image', 'tl_systemIdentifier'                               => 'finished', 'tl_path'  => 'deleted', 'tl_color'=>'000000'),
	);

	private $data = array(
		'tl_ID'               => null,
		'tl_name'             => '',
		'tl_inserted'         => null,
		'tl_ico'              => null,
		'tl_order'            => null,
		'tl_systemIdentifier' => null,
		'tl_path'             => null,
		'tl_author'           => null,
		'tl_color'            => null
	);

	private $users    = array();

	private $projects = array();

	public $onSuccess = array();

	public $onError   = array();

	/**
	 * @param Nette\Database\Connection $db
	 * @throws Nette\InvalidStateException
	 */
	public function __construct(
		\Nette\Database\Context $DB,
		\App\Model\User $User,
		\App\Model\Project $Project = null,
		$ID = null
	) {
		$this->DB      = $DB;
		$this->User    = $User;
		$this->Project = $Project;

		if ($ID) {
			$this->init($this->DB->table('tasks_list')->where(array(
				'tl_ID'      => $ID,
			))->fetch());
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

		if ($this->data['tl_author']) {
			$this->addUser($this->data['tl_author']);
		}

		// $this->get();

		return $this;
	}

	public function getForm() {

		$members = $this->DB->table('users')->fetchPairs('us_ID', 'us_name');

		$form = new UI\Form;;
		$form->addText('tl_name', 'Jméno', 64)
			->addRule(UI\Form::FILLED, 'Vyplňte jméno listu')
			->addCondition(UI\Form::FILLED);

		// $form->addText('tl_color', 'Barva', 16);

		$form['tl_color'] = new \App\Controls\ColorPicker('Barva:');
		$form['tl_color']->setValue('#000000');

		$form->addRadioList('tl_ico', 'Ikona', array(
			'icon-inbox'          => 'Inbox',
			'icon-urgent'         => 'Urgent',
			'icon-finished'       => 'Finished',
			'icon-error_outline'  => 'Alert',
			'icon-drafts'         => 'Drafts',
			'icon-access_time'    => 'Access_time',
			'icon-folder_open'    => 'Folder',
		));

		$form->addCheckboxList('tl_users', 'Members', $members);

		$form->addHidden('tl_ID');
		$form->addHidden('projectName');
		$form->addHidden('projectID');

		$form->addSubmit('send', 'Uložit');

		$form->onError[] = function($form) {
			\Tracy\Debugger::barDump('???? chyba ???');
		};

		$form->onSuccess[] = function($form) {

			$values = $form->getValues();

			foreach($values as $k => $v) {
				if (!in_array($k, array_keys($this->data))) {

					if ($k == 'tl_users') {
						foreach($v as $user) {
							$this->addUser($user);
						}
					}

					if ($k == 'projectID') {
						$this->addProject($v);
					}

					continue;
				}

				$this->data[$k] = $v;
			}

			$this->data['tl_author'] = $this->User->getIdentity()->getId();

			return $this->save();
		};

		return $form;
	}

	public function setProject(\App\Model\Project $Project) {
		$this->Project = $Project;
		return $this;
	}

	public function getProject() {
		return $this->Project;
	}


	public function addUser($ID) {
		$this->users[] = array('us_ID' => $ID);
		return $this;
	}

	public function addProject($ID) {
		$this->projects[] = array('pr_ID' => $ID);
		return $this;
	}

	public function save() {

		$this->data['tl_path'] = \App\Tools::friendly_url($this->data['tl_name']);

		if ($this->data['tl_ID'] > 0) {

			$this->DB
				->table($this->table)
					->where('tl_ID', $this->data['tl_ID'])
					->update($this->data);

		}
		else {
			$this->data['tl_ID'] = null;

			$row = $this->DB
				->table($this->table)
				->insert($this->data);

			if (!$row['tl_ID']) {
				throw \Nette\InvalidArgumentException('Invalid keys for save');
			}

			$this->data['tl_ID'] = $row['tl_ID'];
		}

		// uzivatele
		{
			$this->DB->table($this->tableUser)->where('tasks_list_tl_ID', $this->data['tl_ID'])->delete();

			$is = false;

			foreach($this->users as $user) {
				$row = $this->DB->table($this->tableUser)->insert(array(
					'tasks_list_tl_ID' => $this->data['tl_ID'],
					'users_us_ID' => $user['us_ID']
				));

				if ($user['us_ID'] == $this->data['tl_author']) {
					$is = true;
				}
			}

			// prideleni autora
			if (!$is) {
				$row = $this->DB->table($this->tableUser)->insert(array(
					'tasks_list_tl_ID' => $this->data['tl_ID'],
					'users_us_ID' => $this->data['tl_author']
				));
			}
		}

		// projects
		{
			$this->DB->table($this->tableProjects)->where('tasks_list_tl_ID', $this->data['tl_ID'])->delete();

			foreach($this->projects as $project) {
				if (!$project['pr_ID']) {
					continue;
				}

				$row = $this->DB->table($this->tableProjects)->insert(array(
					'tasks_list_tl_ID' => $this->data['tl_ID'],
					'projects_pr_ID' => $project['pr_ID']
				));

			}
		}


	}

	public function delete() {

	}

	public function getTasks() {
		$list = array();

		foreach($this->DB->table($this->tableTasks)->select('tasks_list_task.tasks_ta_ID AS tasks_ta_ID')->where('tasks_list_tl_ID = ? ', $this->data['tl_ID'])->order('tasks.ta_timeTo ASC, tasks.ta_created DESC') as $join) {
			$list[] = new \App\Model\Task($this->DB, $this->User, $this->Project, $this, $join->tasks_ta_ID);
		}


		return $list;
	}
}
