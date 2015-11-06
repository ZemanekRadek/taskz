<?php
namespace App\Components;

use Nette\Application\UI\Control,
	Tracy\Debugger as Debugger;

class ComponentTaskList extends Control {
	
	private $data = array('neco');
	
	private $List;
	
	public function __construct(\App\Model\TaskList $List) {
		$this->List = $List;
	}
	
	public function render() {
		echo json_encode($this->data);
	}
}
