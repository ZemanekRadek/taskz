<?php
namespace App\Model;

use Nette,
	Nette\Database\Context,
	Tracy\Debugger as Debugger;

class ProjectFactory extends Nette\Object  {

	/** @var Nette\Database\Context @inject */
	private $DB;

	private $User;

	private $table    = 'projects';

	private $tableUser = 'projects_user';

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
		$selection = $this->DB->table($this->tableUser)
			->where('users_us_ID = ? ', $this->User->getIdentity()->us_ID);

		$data = array();

		foreach($selection as $list) {
			$data[] = new \App\Model\Project($this->DB, $this->User, $list->pu_pr_ID);
		}

		return $data;
	}

}
