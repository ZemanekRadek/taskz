<?php
namespace App\Presenters;

use Nette,
	App\Model;
	
class BasePresenter extends Nette\Application\UI\Presenter {
	
	private $defaultLang = 'cs';
	
	public $lang = 'cs';
	
	/** @var \l10nNetteTranslator\Translator @inject */
	public $translator;

	protected function startup() {
		parent::startup();
		
		
		// localization
		try {
			
			$this->lang = $this->getParameter('lang')
					? $this->getParameter('lang')
					: $this->defaultLang;

			$this->translator->testLanguageCode($this->lang);
			$this->translator->setActiveLanguageCode($this->lang);
			
			\App\Model\Language::set($this->lang);
			
		} catch(Nette\InvalidStateException $E) {
			throw new Nette\Application\BadRequestException;
			return;
		}

		if (!$this->getUser()->isLoggedIn()) {
			$this->redirect('Sign:in', array('backlink' => $this->storeRequest()));
		}
		
	}
	
	function beforeRender() {
		$this->template->setTranslator($this->translator);
	}

}