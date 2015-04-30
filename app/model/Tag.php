<?php
namespace App\Model;

use Nette,
	Nette\Application\UI,
	Nette\Database\Context,
	Tracy\Debugger as Debugger;
	
class Tag extends Nette\Object  {
	
	/** @var Nette\Database\Context @inject */
	private $DB;	

	/**
	 * @param Nette\Database\Connection $db
	 * @param Nette\Database\Table\ActiveRow $row
	 * @throws Nette\InvalidStateException
	 */
	public function __construct(
		\Nette\Database\Context $DB,
		\Nette\Database\Table\ActiveRow $row = null
	) {
		$this->DB   = $DB;
		
		if ($row) {
			$this->conversion($row);
		}
	}

	private $table = "tags";
	
	private $data = array(
		'tg_ID', 'tg_title', 'tg_created'
	);
	
	public $raw = null;
	
	/*********************************************************/
	
	public function getForm() {

		$form   = new UI\Form;

		$form->addText('tg_title', 'Název tagu', 128)
			->addRule(UI\Form::FILLED, 'Vyplňte název tagu')
			->addCondition(UI\Form::FILLED);
		
		$form->addHidden('tg_ID');
		$form->addHidden('tg_created');

		$form->addSubmit('tg_send', 'Uložit');
		
		if ($this->raw) {
			$controls = $form->getControls();
			$controls->offsetGet('tg_title')->setValue($this->raw['tg_title']);
			$controls->offsetGet('tg_ID')->setValue($this->raw['tg_ID']);
		}
		
		return $form;
	}

	public function save($values) {
		
		$dataKeys = array_keys($this->data);
		
		foreach(array_keys((array) $values) as $k) {
			if (!in_array($k, $dataKeys)) {
				throw Nette\InvalidArgumentException('Invalid keys for save');
			}
		}
		
		
		// lokalizace pro tag
		$localization = new Localization($this->DB);
		
		if ($values->tg_ID > 0) {

			unset($values['tg_created']);
			
			$this->load($values->tg_ID);
			
			if (!$values['tg_title'] = $localization->save($values['tg_title'], $this->raw['localeID'])) {
				throw Nette\InvalidArgumentException('Invalid create localization');
			}
			
			$this->DB
				->table($this->table)
					->where('tg_ID', $values['tg_ID'])
					->update($values);

		}
		else {
			
			if (!$values['tg_title'] = $localization->save($values['tg_title'])) {
				throw Nette\InvalidArgumentException('Invalid create localization');
			}
			
			$values['tg_created'] = date('Y-m-d H:i:s');
			
			$values = $this->DB
				->table($this->table)
				->insert($values);
				
			if (!$values->tg_ID) {
				throw Nette\InvalidArgumentException('Invalid keys for save');
			}
		}
		
		$this->data = $values;
		
		return true;
	}
	
	public function delete() {
		if (!isset($this->raw['tg_ID']) || !$this->raw['tg_ID']) {
			throw Nette\InvalidArgumentException('Invalid keys for delete');
		}
		
		$this->DB->table($this->table)
			->where('tg_ID', $this->raw['tg_ID'])
				->delete();
				
		$localization = new Localization($this->DB);
		$localization->delete($this->raw['localeID']);
		
		$this->raw = null;
		
		return true;
	}
	
	public function load($ID) {
		
		if (!$row = $this->DB->table($this->table)->where('tg_ID', $ID)->fetch()) {
			throw Nette\Application\BadRequestException('Tag neexistuje');
			return false;
		}
		
		$this->conversion($row);
		
		return $this;
	}
	
	
	private function conversion(Nette\Database\Table\ActiveRow $row) {
		$loc = $row->ref('localization', 'tg_title');
		
		$this->raw = array(
			'tg_ID'    => $row->tg_ID,
			'tg_title' => $loc[Language::get(Localization::PREFIX)],
			'localeID' => $loc->lo_ID
		);
	}

}