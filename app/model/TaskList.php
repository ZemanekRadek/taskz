<?php
namespace App\Model;

use Nette,
	Nette\Database\Context,
	Tracy\Debugger as Debugger;
	
class TaskList extends Nette\Object  {
	
	/** @var Nette\Database\Context @inject */
	private $DB;

	/**
	 * @param Nette\Database\Connection $db
	 * @throws Nette\InvalidStateException
	 */
	public function __construct(\Nette\Database\Context $DB) {
		$this->DB = $DB;
	}

	private $table = "tasks";
	


}