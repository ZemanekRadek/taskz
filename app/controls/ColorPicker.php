<?php

namespace App\Controls;

use Nette\Forms\Form,
	Nette\Utils\Html;

class ColorPicker extends \Nette\Forms\Controls\BaseControl {

	private $color = '#000000';

	public function __construct($label = NULL)
	{
		parent::__construct($label);
		$this->addRule(__CLASS__ . '::validateColor', 'Color is invalid.');
	}
	public function setValue($value) {
		$this->color = $value;
	}
	/**
	 * @return DateTime|NULL
	 */
	public function getValue() {
		return $this->color;
	}
	public function loadHttpData() {
		$c = $this->getHttpData(Form::DATA_LINE);
		$this->color = $c ? $c : '#000000';
	}
	/**
	 * Generates control's HTML element.
	 */
	public function getControl() {
		$name = $this->getHtmlName();

		/*
		<div id="colorPicker">
			<a class="color"><div class="colorInner"></div></a>
			<div class="track"></div>
			<ul class="dropdown"><li></li></ul>
			<input type="hidden" class="colorInput"/>
		</div>
		*/

		return Html::el('div')
			->class('colorPicker')
			->id($name)
			->add(
				Html::el('a')
					->class('color')
					->add(
						Html::el('div')
							->class('colorInner')
					)
			)
			->add(
				Html::el('div')
					->class('track')
			)
			->add(
				Html::el('ul')
					->class('dropdown')
					->add(
						Html::el('li')
					)
			)
			->add(
				Html::el('input')
					->type('hidden')
					->name($name)
					->id($this->getHtmlId())
					->class('colorInput')
					->value($this->color)
			)
			->add(
				Html::el('script')
					->type('text/javascript')
					->setHtml('
					(function(){
						$(\'#' . $name . '\').tinycolorpicker();
						var picker = $(\'#' . $name . '\').data("plugin_tinycolorpicker");
						picker.setColor("' . $this->getValue() . '");
					})(jQuery);
				')
			)
			;

	}
	/**
	 * @return bool
	 */
	public static function validateColor($control) {
		return true;
	}
}
