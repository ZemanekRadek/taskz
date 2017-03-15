<?php

namespace App\Model;

class TagFactory {

	protected $DB;

	public function __construct(\Nette\Database\Context $DB) {
		$this->DB = $DB;
	}

	public function getAll() {
		\Tracy\Debugger::barDump('??');
		return $this->DB->table('tags')->fetchAll();
	}

	public function getFromName($name) {
		return $this->DB->table('tags')->where('tg_name LIKE (?) ', '%' . $name . '%')->fetch();
	}
}
