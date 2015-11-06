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
		
		Debugger::barDump($User);
	}

	
	public function getAll() {
		// $this->DB->table($this->tableUser)->where('us_ID', $this->User->)
	}
	

}