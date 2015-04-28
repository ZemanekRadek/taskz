<?php
namespace App\Model;

use Nette,
	Nette\Application\UI,
	Nette\Database\Context,
	Tracy\Debugger as Debugger;
	
class Localization extends Nette\Object  {
	
	/** @var Nette\Database\Context @inject */
	private $DB;	
	
	private $table = 'localization';
	
	private $data = array(
		'primary' => 'lo_ID'
	);

	/**
	 * @param Nette\Database\Connection $db
	 * @param Model\Language $language
	 * @throws Nette\InvalidStateException
	 */
	public function __construct(
		\Nette\Database\Context $DB
	) {
		$this->DB   = $DB;
	}

	public function get($ID) {
		$loc = $this->DB->table($this->table)->where($this->data['primary'], $ID);
		
		return $loc[Model\Language::get()];
	}
	
	public function save($value, $ID = null) {
		
		if (!is_array($value)) {
			$value = array(
				'lo_CS' => $value,
				'lo_EN' => $value
			);
		}
		
		$row = $this->DB->table($this->table)->insert($value);
		
		
		if (!$row->lo_ID) {
			throw Nette\InvalidArgumentException('Invalid insert data for tag');
		}
		
		return $row->lo_ID;
	}
	
	public function delete($ID) {
		return $this->table($this->table)->where($this->data['primary'], $ID)->delete();
	}
	
	

}