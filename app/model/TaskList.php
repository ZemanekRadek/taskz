<?php
namespace App\Model;

use Nette,
	Nette\Database\Context,
	Tracy\Debugger as Debugger;
	
class TaskList extends Nette\Object  {
	
	/** @var Nette\Database\Context @inject */
	private $DB;
	
	private $User;
	
	private $table = 'tasks';
	
	private $tableUser = 'tasks_user';
	
	
	private $data = array(
		'tl_ID'               => null,
		'tl_name'             => '',
		'tl_userID'           => null,
		'tl_inserted'         => null,
		'tl_ico'              => null,
		'tl_order'            => null,
		'tl_systemIdentifier' => null
	);

	/**
	 * @param Nette\Database\Connection $db
	 * @throws Nette\InvalidStateException
	 */
	public function __construct(
		\Nette\Database\Context $DB,
		\App\Model\User $User,
		$ID = null
	) {
		$this->DB = $DB;
		$this->User = $User;
		
		if ($ID) {
			$this->init($this->DB->table('tasks_list')->where('tl_ID', $ID)->fetch());
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
		return $this->data;
	}

	public function save($data) {
		
	}
	
	public function delete() {
		
	}
}