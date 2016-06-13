<?php
namespace App\Model;

use Nette,
	Nette\Database\Context,
	Tracy\Debugger as Debugger;

class TaskFactory extends Nette\Object  {


		/** @var Nette\Database\Context @inject */
		private $DB;

		private $User;

		private $Project;

		private $List;

		/**
		 * @param Nette\Database\Connection $db
		 * @throws Nette\InvalidStateException
		 */
		public function __construct(
			\Nette\Database\Context $DB,
			\App\Model\User $User,
			\App\Model\Project $Project,
			\App\Model\TaskList $List
		) {
			$this->DB      = $DB;
			$this->User    = $User;
			$this->Project = $Project;
			$this->List    = $List;
		}

		public function getFromList(\App\Model\TaskList $List) {
			$component = new \App\Component\TaskList();
			$component->setList($List);
			return $component;
		}


		public function getAllByUserId() {

		}

		public function getAllByProjectId() {
		}


		public function getAllByTaskListId() {
		}
}
