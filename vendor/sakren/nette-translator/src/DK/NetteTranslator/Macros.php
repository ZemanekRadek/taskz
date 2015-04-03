<?php

namespace DK\NetteTranslator;

use Nette\Latte\Macros\MacroSet;
use Nette\Latte\Compiler;
use Nette\Latte\MacroNode;
use Nette\Latte\PhpWriter;

/**
 *
 * @author David Kudera
 */
class Macros extends MacroSet
{


	/**
	 * @param \Nette\Latte\Compiler $compiler
	 * @return \DK\NetteTranslator\Macros
	 */
	public static function install(Compiler $compiler)
	{
		$me = new static($compiler);		/** @var $me \DK\NetteTranslator\Macros */

		$me->addMacro('_', 'echo %modify($template->translate(%node.args))');

		return $me;
	}

}
 