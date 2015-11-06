<?php
namespace App\Presenters;

use Nette,
	App\Model;
	
class BasePresenter extends Nette\Application\UI\Presenter {
	
	/** @var \l10nNetteTranslator\Translator @inject */
	public $translator;
	
	public $language;
	
	public $lang;
	
	public $user;
	
	public function __construct(\App\Model\Language $lang) {
		$this->language = $lang;
	}

	protected function startup() {
		parent::startup();
		
		// localization
			$this->translator->testLanguageCode($this->language->getLang());
			$this->translator->setActiveLanguageCode($this->language->getLang());
			$this->user = $this->getUser();
			
		if (!$this->user->isLoggedIn()) {
			$this->redirect('Sign:in', array('backlink' => $this->storeRequest()));
		}
		
		
	}
	
	function beforeRender() {
		$this->template->lang = $this->language->getLang();
		$this->template->setTranslator($this->translator);
	}

}