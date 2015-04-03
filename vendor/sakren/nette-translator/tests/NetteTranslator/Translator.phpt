<?php

/**
 * Test: DK\NetteTranslator\Translator
 *
 * @testCase DKTests\NetteTranslator\TranslatorTest
 * @author David Kudera
 */

namespace DKTests\NetteTranslator;

use Tester\TestCase;
use Tester\Assert;
use DK\NetteTranslator\Translator;
use Nette\Caching\Storages\FileJournal;
use Nette\Caching\Storages\FileStorage;

require_once __DIR__. '/bootstrap.php';

/**
 *
 * @author David Kudera
 */
class TranslatorTest extends TestCase
{


	/** @var \DK\NetteTranslator\Translator */
	private $translator;


	protected function setUp()
	{
		$this->translator = new Translator(__DIR__. '/../data');
		$this->translator->setLanguage('en');
	}


	public function testGetCache()
	{
		Assert::null($this->translator->getCache());
	}


	public function testSetCacheStorage()
	{
		$journal = new FileJournal(__DIR__. '/../cache');
		$storage = new FileStorage(__DIR__. '/../cache', $journal);
		$this->translator->setCacheStorage($storage);
		Assert::type('Nette\Caching\Cache', $this->translator->getCache());
	}

}

\run(new TranslatorTest);
