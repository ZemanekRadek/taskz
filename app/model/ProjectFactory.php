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

	protected static $_list = array();

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
		$selection = $this->DB->table($this->tableUser)
			->where('users_us_ID = ? ', $this->User->getIdentity()->us_ID);

		$data = array();

		foreach($selection as $list) {
			$data[] = $this->get($list->projects_pr_ID);
		}

		return $data;
	}

	public function get($ID) {
		if (isset(self::$_list[$ID])) {
			return self::$_list[$ID];
		}

		return self::$_list[$ID] = new \App\Model\Project($this->DB, $this->User, $ID);
	}

}
