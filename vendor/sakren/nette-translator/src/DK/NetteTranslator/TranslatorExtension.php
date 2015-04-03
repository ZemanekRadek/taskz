<?php

namespace DK\NetteTranslator;

use Nette\DI\CompilerExtension;
use Nette\Configurator;
use Nette\DI\Compiler;

/**
 *
 * @author David Kudera
 */
class TranslatorExtension extends CompilerExtension
{


	/** @var array  */
	private $defaults = array(
		'directory' => null,
		'language' => 'en',
		'caching' => false,
		'debugger' => false,
		'debuggerGroups' => array(),
		'replacements' => array(),
	);


	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);

		$translator = $builder->addDefinition($this->prefix('translator'))
			->setClass('DK\NetteTranslator\Translator', array($config['directory']))
			->addSetup('setLanguage', array($config['language']));

		if ($config['caching']) {
			$translator->addSetup('setCacheStorage', array('@Nette\Caching\IStorage'));
		}

		foreach ($config['replacements'] as $name => $value) {
			$translator->addSetup('addReplacement', array($name, $value));
		}

		$builder->addDefinition($this->prefix('helpers'))
			->setClass('DK\NetteTranslator\TemplateHelpers')
			->setFactory($this->prefix('@translator'). '::createTemplateHelpers')
			->setInject(false);

		if ($config['debugger']) {
			$panel = $builder->addDefinition($this->prefix('panel'))
				->setClass('DK\NetteTranslator\Panel')
				->addSetup('register')
				->addTag('run');

			foreach ($config['debuggerGroups'] as $name => $pattern) {
				$panel->addSetup('addGroup', array($name, $pattern));
			}
		}

		$builder->getDefinition('nette.latte')
			->addSetup('DK\NetteTranslator\Macros::install(?->compiler)', array('@self'));
	}


	/**
	 * @param \Nette\Configurator $configurator
	 * @param string $name
	 */
	public static function register(Configurator $configurator, $name = 'translator')
	{
		$configurator->onCompile[] = function($config, Compiler $compiler) use ($name) {
			$compiler->addExtension($name, new TranslatorExtension);
		};
	}

}
 