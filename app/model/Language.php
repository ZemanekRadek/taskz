<?php
namespace App\Model;

use Nette,
	Nette\Application\UI,
	Nette\Database\Context,
	Tracy\Debugger as Debugger;
	
class Language extends Nette\Object  {
	
	private static $lang = '';
	
	private static $_cachePrefix = array();
	
	public static function set($lang) {
		self::$lang = $lang;
	}
	
	public static function get($prefix = null) {
		if (!$prefix) {
			return self::$lang;
		}
		
		if (isset(self::$_cachePrefix[$prefix])) {
			return self::$_cachePrefix[$prefix];
		}
		
		return self::$_cachePrefix[$prefix] = $prefix . strtoupper(self::$lang);
	}


}