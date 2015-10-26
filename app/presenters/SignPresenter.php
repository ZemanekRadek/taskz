<?php

namespace App\Presenters;

use Nette,
	App\Model,
	Nette\Application\UI;


class SignPresenter extends Nette\Application\UI\Presenter {
	
	
	/** @persistent */
	public $backlink = '';
	
	
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
		try {
			$this->getUser()->login($values->us_email, $values->us_password);

		} catch (Nette\Security\AuthenticationException $e) {
			$form->addError($e->getMessage());
			return;
		}

		$this->restoreRequest($this->backlink);
		$this->redirect('Dashboard:');
	}

	public function actionOut() {
		$this->getUser()->logout();
	}


}
