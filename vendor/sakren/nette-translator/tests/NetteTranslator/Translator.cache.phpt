<?php

/**
 * Test: DK\NetteTranslator\Translator
 *
 * @testCase DKTests\NetteTranslator\TranslatorCacheTest
 * @author David Kudera
 */

namespace DKTests\NetteTranslator;

use Tester\Environment;
use Tester\TestCase;
use Tester\Assert;
use DK\NetteTranslator\Translator;
use Nette\Caching\Cache;
use Nette\Caching\Storages\FileJournal;
use Nette\Caching\Storages\FileStorage;

require_once __DIR__. '/bootstrap.php';

/**
 *
 * @author David Kudera
 */
class TranslatorCacheTest extends TestCase
{


	/** @var \DK\NetteTranslator\Translator */
	private $translator;

	/** @var array  */
	private $files = array(
		'../data/web/pages/homepage/en.simple.json' => null
	);


	protected function setUp()
	{
		$journal = new FileJournal(__DIR__. '/../cache');
		$storage = new FileStorage(__DIR__. '/../cache', $journal);
		$this->translator = new Translator(__DIR__. '/../data');
		$this->translator->setLanguage('en');
		$this->translator->setCacheStorage($storage);

		foreach ($this->files as $path =>  &$data) {
			$data = file_get_contents(__DIR__. '/'. $path);
		}
	}


	protected function tearDown()
	{
		$cache = $this->translator->getCache();
		if ($cache !== null) {
			$cache->clean(array(Cache::ALL));
		}

		foreach ($this->files as $path => $data) {
			file_put_contents(__DIR__. '/'. $path, $data);
		}
	}

	public function testSetCache()
	{
		$this->translator->setCache(null);
		Assert::null($this->translator->getCache());
	}


	public function testTranslate()
	{
		$this->translator->translate('web.pages.homepage.promo.title');
		$t = $this->translator->getCache()->load($this->translator->_getCachedCategoryName('web/pages/homepage', 'promo', 'en'));
		Assert::type('array', $t);
		Assert::true(isset($t['title']));
	}


	public function testTranslate_withoutCache()
	{
		$this->translator->setCache(null);
		$t = $this->translator->translate('web.pages.homepage.promo.title');
		Assert::same('Title of promo box', $t);
	}


	public function testInvalidateOnChange()
	{
		Environment::skip();		// @todo

		Assert::same('Title of promo box', $this->translator->translate('web.pages.homepage.simple.title'));

		$path = __DIR__. '/../data/web/pages/homepage/en.simple.json';
		file_put_contents($path, '{"title": "New title"}');

		$this->translator->getCache()->release();
		$this->translator->invalidate();

		Assert::null($this->translator->translate('web.pages.homepage.simple.title'));
	}

}

\run(new TranslatorCacheTest);
