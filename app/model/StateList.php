<?php
namespace App\Model;

use Nette,
	Nette\Application\UI,
	Nette\Database\Context,
	Tracy\Debugger as Debugger;
	
class StateList extends Nette\Object  {
	
	/** @var Nette\Database\Context @inject */
	private $DB;
	
	private $list;

	/**
	 * @param Nette\Database\Connection $db
	 * @throws Nette\InvalidStateException
	 */
	public function __construct(
		\Nette\Database\Context $DB
	) {
		$this->DB = $DB;
	}

	public function get() {
		$list = array();
		
		foreach($this->DB->query('
			SELECT 
				* 
			FROM 
				states 
			LEFT JOIN 
				localization ON (lo_ID = st_title)
		')->fetchAll() as $state) {
			$list[$state->st_ID] = $state[Language::get('lo_')];
		}
		
		return $list;
	}
}