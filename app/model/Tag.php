<?php
namespace App\Model;

use Nette,
	Nette\Application\UI,
	Nette\Database\Context,
	Tracy\Debugger as Debugger;

class Tag extends Nette\Object  {

	/** @var Nette\Database\Context @inject */
	private $DB;

	private $table = "tags";

	private $data = array(
		'tg_ID'    => null,
		'tg_name'  => null,
		'tg_color' => null
	);

	public $raw = null;

	/**
	 * @param Nette\Database\Connection $db
	 * @param Nette\Database\Table\ActiveRow $row
	 * @throws Nette\InvalidStateException
	 */
	public function __construct(
		\Nette\Database\Context $DB,
		$ID = null
	) {
		$this->DB   = $DB;

		if ($ID) {
			$this->load($ID);
		}
	}

	public function & __get($name) {
		if (in_array($name, array_keys($this->data))) {
			return $this->data[$name];
		}

		return parent::__get($name);
	}
	/*********************************************************/



	public function getForm() {

		$form   = new UI\Form;

		$form->addText('tg_name', 'Název tagu', 128)
			->addRule(UI\Form::FILLED, 'Vyplňte název tagu')
			->addCondition(UI\Form::FILLED);

		$form->addHidden('tg_ID');
		$form->addHidden('tg_color');

		$form->addSubmit('tg_send', 'Uložit');

		if ($this->raw) {
			$controls = $form->getControls();
			$controls->offsetGet('tg_name')->setValue($this->raw['tg_name']);
			$controls->offsetGet('tg_ID')->setValue($this->raw['tg_ID']);
		}

		return $form;
	}

	public function init($data) {
		$keys = array_keys($this->data);
		foreach($data as $k => $v) {
			if (!in_array($k, $keys)) {
				continue;
			}
			$this->data[$k] = $v;
		}

		return $this;
	}

	public function save() {

		$values = $this->data;

		if ($values['tg_ID'] > 0) {

			$this->DB
				->table($this->table)
					->where('tg_ID', $values['tg_ID'])
					->update($values);

		}
		else {

			unset($values['tg_ID']);

			$row = $this->DB
				->table($this->table)
				->insert($values);

			if (!$row['tg_ID']) {
				return false;
				throw Nette\InvalidArgumentException('Invalid keys for save');
			}

			$this->data['tg_ID'] = $row['tg_ID'];
		}

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
		$loc = $row->ref('localization', 'tg_name');

		$this->raw = array(
			'tg_ID'    => $row->tg_ID,
			'tg_name'  => $loc[Language::get(Localization::PREFIX)],
			'tg_color' => $row->tg_color,
			'localeID' => $loc->lo_ID
		);
	}

}
