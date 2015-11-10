<?php
namespace App\Model;

use Nette,
	Nette\Security,
	Tracy\Debugger as Debugger;
	
class User extends Nette\Object  {
	
	/** @var Nette\Database\Context @inject */
	private $db;

	private $table = "users";
	
	public static $salt = 'xXy';
	
	public static $identity = null;
	
	/**
	 * @param Nette\Database\Connection $db
	 * @throws Nette\InvalidStateException
	 */
	public function __construct(\Nette\Database\Context $db) {
		$this->db = $db;
	}
	
	public static function hash($text) {
		return sha1($text . self::$salt);
	}
	
	public static function verify($pass, $hash) {
		return self::hash($pass) == $hash;
	}

	public function registration($data) {
		unset($data["us_password2"]);
		$data['us_groupID'] = 1;
		$data["us_password"] = self::hash($data["us_password"]);
		return $this->db->table($this->table)->insert($data);
	}
	
	public function check($data) {
		$field = $this->db
						->table($this->table)
							->where('us_email',$data['us_email'])
							->fetch();
			
			
		return isset($field['us_ID']) && $field['us_ID'];
	}
	
	public function getIdentity() {
		return self::$identity;
	}
}