<?php
namespace App\Model;

use Nette,
	Tracy\Debugger as Debugger;
	
class Options extends Nette\Object  {
	
	/** @var Nette\Database\Context @inject */
	private $db;

	/** @var App\Model\User @inject */
	private $user;

	private $table = "options";
	
	/**
	 * @param Nette\Database\Connection $db
	 * @throws Nette\InvalidStateException
	 */
	public function __construct(\Nette\Database\Context $db) {
		$this->db = $db;
		
		// $this->db->table('options')->where(array(''))
	}
	
}