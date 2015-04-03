<?php

namespace DK\NetteTranslator;

use Nette\Object;
use Nette\Utils\Html;
use Nette\Latte\Engine;

/**
 *
 * @author David Kudera
 */
class TemplateHelpers extends Object
{


	/** @var \DK\NetteTranslator\Translator  */
	private $translator;


	/**
	 * @param \DK\NetteTranslator\Translator $translator
	 */
	public function __construct(Translator $translator)
	{
		$this->translator = $translator;
	}


	/**
	 * @param string $method
	 * @return \Nette\Callback
	 */
	public function loader($method)
	{
		if (method_exists($this, $method)) {
			return callback($this, $method);
		}
	}


	/**
	 * @return \DK\NetteTranslator\Translator
	 */
	public function getTranslator()
	{
		return $this->translator;
	}


	/**
	 * @param string $message
	 * @param int|null $count
	 * @param array $args
	 * @return \Nette\Utils\Html|string
	 */
	public function translate($message, $count = null, array $args = array())
	{
		$result = $this->translator->translate($message, $count, $args);
		$last = $this->translator->getLastTranslated();

		if (!$last) {
			return $result;
		}

		if ($this->translator->isDebugMode()) {
			$result .= ' <small><i>('. $last. ')</i></small>';
		}

		return Html::el()->setHtml($result)->style(array('color' => 'red'));
	}

}
 