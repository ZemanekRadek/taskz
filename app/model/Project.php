<?php
namespace App\Model;

use Nette,
	Nette\Database\Context,
	Nette\Application\UI,
	Tracy\Debugger as Debugger;
	
class Project extends Nette\Object  {
	
	/** @var Nette\Database\Context @inject */
	private $DB;
	
	private $User;
	
	private $table = "projects";
	
	private $data  = array(
		'pr_ID'    => null,
		'pr_name'  => null,
		'pr_path'  => null
	);
	
	public static $actualProject = null;
	
	/**
	 * @param Nette\Database\Connection $db
	 * @throws Nette\InvalidStateException
	 */
	public function __construct(
		\Nette\Database\Context $db,
		\App\Model\User $User,
		$ID = null
	) {
		$this->DB   = $db;
		$this->User = $User;
		Debugger::barDump(self::$actualProject);
		
		if ($ID) {
			$this->init($this->DB->table($this->table)->where('pr_ID', $ID)->fetch());
		}
		else if (self::$actualProject) {
			$this->init($this->DB->table($this->table)->where('pr_ID', self::$actualProject)->fetch());
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
		
		$this->data['pr_path'] = \App\Tools::friendly_url($this->data['pr_name']);
		
		return $this->data;
	}
	
	public function getForm() {
		$form = new UI\Form;;
		$form->addText('pr_name', 'Jméno', 64)
			->addRule(UI\Form::FILLED, 'Vyplňte jméno projektu')
			->addCondition(UI\Form::FILLED);
		$form->addHidden('pr_ID');

		$form->addSubmit('send', 'Uložit');
		
		$form->onSuccess[] = array($this, 'saveProject');
		
		return $form;
	}
	
	public function save() {
		
		/*
		$dataKeys = array_keys($this->data);
		
		foreach(array_keys((array) $values) as $k) {
			if (!in_array($k, $dataKeys)) {
				throw Nette\InvalidArgumentException('Invalid keys for save');
			}
		}
		*/
		
		
		$values = clone $this->data;
		
		$values->pr_path = \App\Tools::friendly_url($values->pr_name);
		
		if ($values->pr_ID > 0) {

			$this->DB
				->table($this->table)
					->where('pr_ID', $values['pr_ID'])
					->update($values);

		}
		else {
			$this->DB
				->table($this->table)
				->insert($values);
				
			if (!$values['pr_ID'] = $this->DB->getInsertId()) {
				throw Nette\InvalidArgumentException('Invalid keys for save');
			}
		}
		
		$this->data = $values;
		
		return true;
	}
	
	public function existFromName($name) {
		$p = $this->DB
			->table($this->table)
				->where('pr_name', $name)->fetch();
				
		return isset($p->pr_ID) && $p->pr_ID;
	}
	
	public function loadDefault() {
		$this->init($this->DB->table($this->table)->get(1));
		return $this;
	}
	
	public function set($ID) {
		
	}
	

}