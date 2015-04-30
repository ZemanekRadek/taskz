<?php
namespace App\Model;

use Nette,
	Nette\Security,
	Tracy\Debugger as Debugger;
	
class UserList extends Nette\Object  {
	
	/** @var Nette\Database\Context @inject */
	private $db;

	private $table = "users";
	
	/**
	 * @param Nette\Database\Connection $db
	 * @throws Nette\InvalidStateException
	 */
	public function __construct(\Nette\Database\Context $db) {
		$this->db = $db;
	}

	public function getAll() {
		$list = array();
		foreach($this->db->table('users')->fetchAll() as $user) {
			$list[$user->us_ID] = $user->us_email;
		}
		
		return $list;
	}
}