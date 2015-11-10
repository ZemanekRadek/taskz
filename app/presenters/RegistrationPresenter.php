<?php

namespace App\Presenters;

use Nette,
	Nette\Application\UI;


class RegistrationPresenter extends Nette\Application\UI\Presenter {        
	/** @persistent */
	public $backlink = ''; 
	
	/** @var \App\Model\User @inject */
	public $user;
	
	protected function createComponentRegistrationForm() {
		$form = new UI\Form;;
		$form->addText('us_name', 'Jméno');
		
		$form->addText('us_email', 'E-mail: *', 64)
				// ->setEmptyValue('@')
				->addCondition(UI\Form::FILLED)
				->addRule(UI\Form::FILLED, 'Vyplňte Váš email')
				->addRule(UI\Form::EMAIL, 'Neplatná emailová adresa');
				
		$form->addPassword('us_password', 'Heslo: *', 20)
				->setOption('description', 'Alespoň 6 znaků')
				->addRule(UI\Form::FILLED, 'Vyplňte Vaše heslo')
				->addRule(UI\Form::MIN_LENGTH, 'Heslo musí mít alespoň %d znaků.', 6);
				
		$form->addPassword('us_password2', 'Heslo znovu: *', 20)
				->addConditionOn($form['us_password'], UI\Form::VALID)
				->addRule(UI\Form::FILLED, 'Heslo znovu')
				->addRule(UI\Form::EQUAL, 'Hesla se neshodují.', $form['us_password2']);
				
		$form->addSubmit('send', 'Registrovat');
		
		$form->onSuccess[] = array($this, 'registrationFormSubmitted');
		
		return $form;
	}
	
	
	public function registrationFormSubmitted($form, $values) {
		                                    
		$pass = (string) $values['us_password'];
		
		try {
			
			if ($this->user->check($values)) {
				$form->addError('Tento uživatel již existuje');
				return;
			}
			
			if (!$this->user->registration($values)) {
				return;
			}
			
			// nahrani defaultnich seznamu
			// $TaskFactory
			
			
		} catch (Nette\Security\AuthenticationException $e) {
			$form->addError($e->getMessage());
			return;
		}
		
		
		$this->getUser()->login($values['us_email'], $pass);
		$this->restoreRequest($this->backlink);
		$this->redirect('Dashboard:');
		
		return true;
	}

}