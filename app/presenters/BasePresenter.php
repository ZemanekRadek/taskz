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

	// public $project;

	public $DB;

	public function __construct(
		\App\Model\Language $lang,
		\Nette\Database\Context $DB
	) {
		$this->language = $lang;
		$this->DB       = $DB;
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

		// identity
		\App\Model\User::$identity = $this->user->getIdentity();

		// project
		if ($this->getRequest()->getPresenterName() == 'List') {

		}
		// Debugger::barDump($this->project);

	}

	function beforeRender() {
		$this->template->lang = \App\Model\Language::get();
		$this->template->setTranslator($this->translator);
	}

}
