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

		public function setProject(\App\Model\Project $Project) {
			$this->Project = $Project;
		}

		public function getFromList(\App\Model\TaskList $List) {
			$component = new \App\Component\TaskList();
			$component->setList($List);
			$component->setProject($this->Project);
			$component->setUser($this->User);
			return $component;
		}

		public function getAll() {

			$list = array();

			foreach($this->DB->table('tasks_user')->where('users_us_ID = ? ', $this->User->getIdentity()->id) as $join) {
				$list[] = new \App\Model\Task($this->DB, $this->User, $this->Project, $this->List, $join->tasks_ta_ID);
			}

			$component = new \App\Component\TaskList();
			$component->setTasks(
				$list
			);
			$component->setProject($this->Project);
			$component->setUser($this->User);
			return $component;
		}

		public function getAllByUserId() {

		}

		public function getAllByProjectId() {
		}


		public function getAllByTaskListId($id) {

		}

		public function getById($id) {
			$task = new \App\Model\Task($this->DB, $this->User, $this->Project, $this->List, $id);
			return $task;
		}

		public function getForm() {
			$form = new \App\Component\TaskForm($this->DB, $this->User, $this->Project, $this->List);
			return $form;
		}

}
