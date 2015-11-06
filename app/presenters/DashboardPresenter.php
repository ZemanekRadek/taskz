<?php
namespace App\Presenters;

use Nette,
	App\Model;
	
class DashboardPresenter extends BasePresenter {
	
	private $List;
	
	public function __construct(\App\Model\TaskList $TaskList, \App\Model\Language $lang) {
		parent::__construct($lang);
		
		$this->List = $TaskList;
	}
	
	
	public function createComponentTaskList() {
		$Component = new \App\Components\ComponentTaskList($this->List);
		return $Component;
	}

}