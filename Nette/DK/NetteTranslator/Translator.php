<?php

namespace DK\NetteTranslator;

use DK\Translator\Translator as DKTranslator;
use Nette\Localization\ITranslator;
use Nette\Caching\IStorage;
use Nette\Caching\Cache;
use Nette\Http\IRequest;

/**
 *
 * @author David Kudera
 */
class Translator extends DKTranslator implements ITranslator
{


	/** @var \Nette\Caching\Cache */
	private $cache;

	/** @var bool  */
	private $debugMode = false;


	/**
	 * @param \DK\Translator\Loaders\Loader|string $pathOrLoader
	 * @param \Nette\Http\IRequest $httpRequest
	 */
	public function __construct($pathOrLoader, IRequest $httpRequest = null)
	{
		parent::__construct($pathOrLoader);

		if ($httpRequest !== null) {
			$this->debugMode = (bool) $httpRequest->getCookie(Panel::COOKIE_DEBUG_KEY);
		}
	}


	/**
	 * @return bool
	 */
	public function isDebugMode()
	{
		return $this->debugMode;
	}


	/**
	 * @param \Nette\Caching\IStorage $cacheStorage
	 * @return \DK\NetteTranslator\Translator
	 */
	public function setCacheStorage(IStorage $cacheStorage)
	{
		$this->cache = new Cache($cacheStorage, 'translator');
		return $this;
	}


	/**
	 * @return \Nette\Caching\Cache
	 */
	public function getCache()
	{
		return $this->cache;
	}


	/**
	 * @param \Nette\Caching\Cache $cache
	 * @return \DK\NetteTranslator\Translator
	 */
	public function setCache(Cache $cache = null)
	{
		$this->cache = $cache;
		return $this;
	}


	/**
	 * @param string $path
	 * @param string $name
	 * @param string $language
	 * @return string
	 */
	public function _getCachedCategoryName($path, $name, $language)
	{
		return $language. ':'. $path. '/'. $name;
	}


	/**
	 * @param string $path
	 * @param string $name
	 * @param null|string $language
	 * @return array
	 */
	public function _loadCategory($path, $name, $language = null)
	{
		if ($language === null) {
			$language = $this->getLanguage();
		}

		if ($this->cache === null) {
			return parent::_loadCategory($path, $name, $language);
		}

		$cachedName = $this->_getCachedCategoryName($path, $name, $language);
		$data = $this->cache->load($cachedName);

		if ($data === null) {
			$data = parent::_loadCategory($path, $name, $language);
			$this->cache->save($cachedName, $data, array(
				Cache::FILES => array($this->getLoader()->getFileSystemPath($path, $name, $language))
			));
		}

		return $data;
	}


	/**
	 * @return \DK\NetteTranslator\TemplateHelpers
	 */
	public function createTemplateHelpers()
	{
		return new TemplateHelpers($this);
	}

}