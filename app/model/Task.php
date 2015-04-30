<?php
namespace App\Model;

use Nette,
	Nette\Application\UI,
	Nette\Database\Context,
	Tracy\Debugger as Debugger;
	
class Task extends Nette\Object  {
	
	/** @var Nette\Database\Context @inject */
	private $DB;

	/** @var Nette\Security\User @inject */
	private $User;

	/**
	 * @param Nette\Database\Connection $db
	 * @throws Nette\InvalidStateException
	 */
	public function __construct(
		\Nette\Database\Context $DB,
		\Nette\Security\User $User
	) {
		$this->DB   = $DB;
		$this->User = $User;
	}

	private $table = "tasks";
	
	private $data  = array(
		'ta_ID'   => null,
	);
	
	public function getForm() {
		
		

		$form   = new UI\Form;
		$states = new StateList($this->DB);
		$users  = new UserList($this->DB);
		$tags   = new TagList($this->DB);

		$form->addText('ta_name', 'Název úkolu', 128)
			->addRule(UI\Form::FILLED, 'Vyplňte název úkolu')
			->addCondition(UI\Form::FILLED);
		
		$form->addTextArea('ta_description', 'Popis úkolu');
		
		$form->addSelect('ta_state', 'Stav', $states->get());
		$form->addText('ta_urgent', 'Urgent');
		
		$form->addText('ta_timeTo', 'Splnit do')
			->addRule(UI\Form::PATTERN, 'Špatný formát datumu', '[0-9]{2}\.[0-9]{2}\.[0-9]{4}');
		
		$form->addCheckboxList('ta_users', 'Uživatelé', $users->getAll());
		$form->addCheckboxList('ta_tags', 'Tagy', $tags->getAll(true));
		
		$form->addHidden('ta_ID');
		$form->addHidden('ta_created');
		$form->addHidden('ta_author', $this->User->getId());

		$form->addSubmit('ta_send', 'Uložit');
		
		$form->onSuccess[] = array($this, 'saveTask');
		
		return $form;
	}

	
	public function save($values) {
		
		$dataKeys = array_keys($this->data);
		
		foreach(array_keys((array) $values) as $k) {
			if (!in_array($k, $dataKeys)) {
				throw Nette\InvalidArgumentException('Invalid keys for save');
			}
		}
		
		if ($values->ta_ID > 0) {

			$this->db
				->table($this->table)
					->where('ta_ID', $values['ta_ID'])
					->update($values);

		}
		else {
			$this->db
				->table($this->table)
				->insert($values);
				
			if (!$values['ta_ID'] = $this->db->getInsertId()) {
				throw Nette\InvalidArgumentException('Invalid keys for save');
			}
		}
		
		$this->data = $values;
		
		return true;
	}
	
	

}