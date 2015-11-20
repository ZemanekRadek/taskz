<?php

namespace App\Presenters;

use Nette,
	Nette\Application\UI;


class RegistrationPresenter extends Nette\Application\UI\Presenter {
	/** @persistent */
	public $backlink = ''; 
	
	/** @var \App\Model\User @inject */
	public $user;
	
	public $DB;
	
	public $language;
	
	public function __construct(
		\App\Model\Language $lang,
		\Nette\Database\Context $DB
	) {
		$this->language = $lang;
		$this->DB       = $DB;
	}
	
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
			
			$this->DB->beginTransaction();
			
			if (!$userID = $this->user->registration($values)) {
				return;
			}
			
			// nahrani defaultnich seznamu a projektu pri registraci
			$Project = new \App\Model\Project($this->DB, $this->user);
			$Project
				->init(array(
					'pr_name'   => 'MyProject',
					'pr_author' => $userID
				));
			$Project
				->save();
			
			$this->DB->commit();
			
			
			
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