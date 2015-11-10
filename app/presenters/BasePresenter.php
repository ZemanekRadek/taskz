<?php
namespace App\Presenters;

use Nette,
	Tracy\Debugger as Debugger,
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

	public function startup() {
		parent::startup();
		
		// localization
			$this->translator->testLanguageCode(\App\Model\Language::get());
			$this->translator->setActiveLanguageCode(\App\Model\Language::get());
			$this->user = $this->getUser();
			
		if (!$this->user->isLoggedIn()) {
			$this->redirect('Sign:in', array('backlink' => $this->storeRequest()));
		}
		
		\App\Model\User::$identity = $this->user->getIdentity();
		
		
	}
	
	function beforeRender() {
		$this->template->lang = \App\Model\Language::get();
		$this->template->setTranslator($this->translator);
	}

}