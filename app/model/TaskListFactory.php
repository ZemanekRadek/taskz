<?php
namespace App\Model;

use Nette,
	Nette\Database\Context,
	Tracy\Debugger as Debugger;
	
class TaskListFactory extends Nette\Object  {
	
	/** @var Nette\Database\Context @inject */
	private $DB;
	
	private $User;
	
	private $table = 'tasks_list';
	
	private $tableUser = 'tasks_list_user';
	
	/**
	 * @param Nette\Database\Connection $db
	 * @throws Nette\InvalidStateException
	 */
	public function __construct(
		\Nette\Database\Context $DB,
		\App\Model\User $User
	) {
		$this->DB = $DB;
		$this->User = $User;
	}
	
	public function getAll() {
		
		// Debugger::barDump($this->User->getIdentity());
		$selection = $this->DB->table('tasks_list_user')
			->where('tlu_us_ID = ? ', $this->User->getIdentity()->us_ID);
		
		$data = array();
		
		foreach($selection as $list) {
			$data[] = new \App\Model\TaskList($this->DB, $this->User, $list->tlu_tl_ID);
		}
		
		Debugger::barDump($data);
		
		return $data;
		
		
	}

}