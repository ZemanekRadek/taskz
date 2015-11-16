<?php
namespace App\Model;

use Nette,
	Nette\Database\Context,
	Tracy\Debugger as Debugger;
	
class TaskList extends Nette\Object  {
	
	/** @var Nette\Database\Context @inject */
	private $DB;
	
	private $User;
	
	public $Project;
	
	private $table = 'tasks_list';
	
	private $tableUser = 'tasks_list_user';
	
	private $tableProjects = 'tasks_list_project';
	
	// system list identifier
	public static $system = array(
		'inbox'     => array('tl_name' => 'Inbox', 'tl_ico'    => 'ico-inbox', 'tl_systemIdentifier'    => 'inbox', 'tl_path' => 'inbox'),
		'urgent'    => array('tl_name' => 'Urgent', 'tl_ico'   => 'ico-urgent', 'tl_systemIdentifier'   => 'urgent', 'tl_path' => 'urgent'),
		'finished'  => array('tl_name' => 'Finished', 'tl_ico' => 'ico-finished', 'tl_systemIdentifier' => 'finished', 'tl_path' => 'finished'),
	);
	
	private $data = array(
		'tl_ID'               => null,
		'tl_name'             => '',
		'tl_inserted'         => null,
		'tl_ico'              => null,
		'tl_order'            => null,
		'tl_systemIdentifier' => null,
		'tl_path'             => null,
		'tl_author'           => null
	);
	
	private $users    = array();
	
	private $projects = array();

	/**
	 * @param Nette\Database\Connection $db
	 * @throws Nette\InvalidStateException
	 */
	public function __construct(
		\Nette\Database\Context $DB,
		\App\Model\User $User,
		\App\Model\Project $Project,
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
	
	public function setProject(\App\Model\Project $Project) {
		$this->Project = $Project;
		return $this;
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
			$this->DB->table($this->tableUser)->where('tlu_tl_ID', $this->data['tl_ID'])->delete();
			
			foreach($this->users as $user) {
				$row = $this->DB->table($this->tableUser)->insert(array(
					'tlu_tl_ID' => $this->data['tl_ID'],
					'tlu_us_ID' => $user['us_ID']
				));
			}
		}
		
		// projects
		{
			$this->DB->table($this->tableProjects)->where('tlp_tl_ID', $this->data['tl_ID'])->delete();
			
			foreach($this->projects as $project) {
				$row = $this->DB->table($this->tableProjects)->insert(array(
					'tlp_tl_ID' => $this->data['tl_ID'],
					'tlp_pr_ID' => $project['pr_ID']
				));
			}
		}
		
	}
	
	public function delete() {
		
	}
}