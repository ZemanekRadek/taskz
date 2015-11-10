<?php

namespace App\Presenters;

use Nette,
	App\Model,
	Tracy\Debugger as Debugger,
	Nette\Application\UI;


class SignPresenter extends Nette\Application\UI\Presenter {
	
	
	/** @persistent */
	public $backlink = '';
	
	/** @var \l10nNetteTranslator\Translator @inject */
	public $translator;
	
	public function __construct(\App\Model\Language $Language) {
	}

	protected function startup() {
		parent::startup();
		
		$this->autoCanonicalize = false;
		
		// localization
			$this->translator->testLanguageCode(\App\Model\Language::get());
			$this->translator->setActiveLanguageCode(\App\Model\Language::get());

		if ($this->getUser()->isLoggedIn()) {
			$this->getUser()->logout();
			//$this->redirect('Sign:in', array('backlink' => $this->storeRequest()));
			//$this->redirect('Sign:in', array('backlink' => $this->storeRequest()));
		}
		
	}
	
	
	/**
	 * Sign-in form factory.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentSignInForm()
	{
		$form = new UI\Form;
		$form->addText('us_email', 'Username:')
			->setRequired('Please enter your username.');

		$form->addPassword('us_password', 'Password:')
			->setRequired('Please enter your password.');

		$form->addSubmit('send', 'Sign in');

		$form->onSuccess[] = array($this, 'signInFormSucceseded');
		return $form;
	}


	public function signInFormSucceseded($form, $values)
	{
		
		Debugger::barDump($form->getComponent('us_email'));
		try {
			$this->getUser()->login($values->us_email, $values->us_password);
			
		} catch (Nette\Security\AuthenticationException $e) {
			$form->getComponent('us_email')->addError('user_invalid');
			$form->addError($e->getMessage());
			return;
		}
		
		$this->restoreRequest($this->backlink);
		$this->redirect('Dashboard:');
	}

	public function actionOut() {
		$this->getUser()->logout();
	}
	
	function beforeRender() {
		$this->template->lang = \App\Model\Language::get();
		$this->template->setTranslator($this->translator);
	}


}
