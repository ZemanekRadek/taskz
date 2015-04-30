<?php
namespace App\Model;

use Nette,
	Nette\Application\UI,
	Nette\Database\Context,
	Tracy\Debugger as Debugger;
	
class TagList extends Nette\Object  {
	
	/** @var \Nette\Database\Context @inject */
	private $DB;
	
	private $table = "tags";

	/**
	 * @param Nette\Database\Connection $db
	 * @throws Nette\InvalidStateException
	 */
	public function __construct(
		\Nette\Database\Context $DB
	) {
		$this->DB   = $DB;
	}

	public function getAll($assoc = false) {
		$list         = array();
		$localization = new Localization($this->DB);
		
		foreach($this->DB->table($this->table)->fetchAll() as $tag) {
			if ($assoc) {
				$list[$tag->tg_ID] = $tag->ref('localization', 'tg_title')[Language::get(Localization::PREFIX)];
				continue;
			}
			
			$list[] = new Tag($this->DB, $tag);
		}

		return $list;
	}
}