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
	
	const PREFIX = 'lo_';
	
	private $data = array(
		'primary' => 'lo_ID',
		'locales' => array(
			'lo_CS', 'lo_EN'
		)
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
			$arr = array();
			foreach($this->data['locales'] as $loc) {
				$arr[$loc] = $value;
			}
			$value = $arr;
		}
		
		$row = $this->DB->table($this->table);
		
		if ($ID) {
			$row->where('lo_ID', $ID)->update($value);
			
			return $ID;
		}
		else {
			$row = $row->insert($value);
		}
		
		
		if (!$row->lo_ID) {
			throw Nette\InvalidArgumentException('Invalid insert data for tag');
		}
		
		return $row->lo_ID;
	}
	
	public function delete($ID) {
		return $this->DB->table($this->table)->where($this->data['primary'], $ID)->delete();
	}
	
	

}