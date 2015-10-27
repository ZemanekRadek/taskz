<?php
namespace App\Model;

use Nette,
	Nette\Application\UI,
	Nette\Database\Context,
	Tracy\Debugger as Debugger;
	
class Language extends Nette\Object  {
	
	public static $lang = '';
	
	public static $_cachePrefix = array();
	
	public static $Request = null;
	
	public static $languages = array(
		'cs', 'en'
	);
	
	public function __construct(Nette\Http\Request $Request) {
		$lang = false;
		$url  = trim($Request->getUrl()->getPath(), '/');
		foreach(explode('/', $url) as $path) {
			if (preg_match('#[a-z]{2}#', $path) && in_array($path, self::$languages)) {
				$lang = $path;
				break;
			}
			
			break;
		}
		
		self::$lang = $lang ? $lang : 'cs';
	}
	
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
	
	public function getLang() {
		return self::$lang;
	}

}