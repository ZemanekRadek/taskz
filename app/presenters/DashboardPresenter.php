<?php
namespace App\Presenters;

use Nette,
	App\Model;
	
class DashboardPresenter extends Nette\Application\UI\Presenter {

	
	protected function startup() {
		parent::startup();

		if (!$this->getUser()->isLoggedIn()) {
			$this->redirect('Sign:in', array('backlink' => $this->storeRequest()));
		}
	}

}