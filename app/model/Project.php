<?php
namespace App\Model;

use Nette,
	Nette\Database\Context,
	Tracy\Debugger as Debugger;
	
class Project extends Nette\Object  {
	
	/** @var Nette\Database\Context @inject */
	private $db;

	/**
	 * @param Nette\Database\Connection $db
	 * @throws Nette\InvalidStateException
	 */
	public function __construct(\Nette\Database\Context $db) {
		$this->db = $db;
	}

	private $table = "projects";
	
	private $data  = array(
		'pr_ID'   => null,
		'pr_name' => null
	);
	
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
	
	public function save($values) {
		
		$dataKeys = array_keys($this->data);
		
		foreach(array_keys((array) $values) as $k) {
			if (!in_array($k, $dataKeys)) {
				throw Nette\InvalidArgumentException('Invalid keys for save');
			}
		}
		
		if ($values->pr_ID > 0) {

			$this->db
				->table($this->table)
					->where('pr_ID', $values['pr_ID'])
					->update($values);

		}
		else {
			$this->db
				->table($this->table)
				->insert($values);
				
			if (!$values['pr_ID'] = $this->db->getInsertId()) {
				throw Nette\InvalidArgumentException('Invalid keys for save');
			}
		}
		
		$this->data = $values;
		
		return true;
	}
	
	public function existFromName($name) {
		$p = $this->db
			->table($this->table)
				->where('pr_name', $name)->fetch();
				
		return isset($p->pr_ID) && $p->pr_ID;
	}
	
	public function set($ID) {
		
	}
	

}