<?php
namespace App\Model;

use Nette,
	Nette\Database\Context,
	Tracy\Debugger as Debugger;

class TaskListFactory extends Nette\Object  {

	/** @var Nette\Database\Context @inject */
	private $DB;

	private $User;

	private $Project;

	private $ProjectFactory;

	private $table = 'tasks_list';

	private $tableUser = 'tasks_list_user';

	/**
	 * @param Nette\Database\Connection $db
	 * @throws Nette\InvalidStateException
	 */
	public function __construct(
		\Nette\Database\Context $DB,
		\App\Model\User $User,
		\App\Model\Project $Project,
		\App\Model\ProjectFactory $ProjectFactory
	) {
		$this->DB             = $DB;
		$this->User           = $User;
		$this->Project        = $Project;
		$this->ProjectFactory = $ProjectFactory;
	}

	public function setProject(\App\Model\Project $Project) {
		$this->Project = $Project;
	}

	public function getAll($options = array()) {

//		Debugger::barDump($this->Project);
	// return array();

		$selection = $this->DB->table('tasks_list_user')
			->where('users_us_ID = ? ', $this->User->getIdentity()->us_ID);

		$data     = array();
		$projects = array();

		foreach($selection as $list) {

			if (isset($options['withoutProject']) && $options['withoutProject']) {
				foreach($this->DB->table('projects_user')
					->where('users_us_ID', $this->User->getIdentity()->us_ID) as $pr) {

					if (!isset($projects[$list->tasks_list_tl_ID])) {
						$projects[$list->tasks_list_tl_ID] = $this->ProjectFactory->get($pr->projects_pr_ID); // new \App\Model\Project($this->DB, $this->User, $pr->projects_pr_ID);
					}

					break;
				}

			}
			else {

				foreach($this->DB->table('tasks_list_project')
					->where('tasks_list_tl_ID', $list->tasks_list_tl_ID) as $pr) {

					if (!isset($projects[$list->tasks_list_tl_ID])) {
						$projects[$list->tasks_list_tl_ID] = $this->ProjectFactory->get($pr->projects_pr_ID); //new \App\Model\Project($this->DB, $this->User, $pr->projects_pr_ID);
					}

					break;
				}

				if ($this->Project && $pr) {
					if ($pr->projects_pr_ID != $this->Project->pr_ID) {
						continue;
					}
				}
			}

			$data[] = new \App\Model\TaskList($this->DB, $this->User, $projects[$list->tasks_list_tl_ID], $list->tasks_list_tl_ID);
		}

		// Debugger::barDump($data);

		return $data;
	}

	public function getAllAsPairs($all = false) {
		$data = array();
		foreach($this->getAll($all ? array('withoutProject' => 1) : array()) as $list) {
			$data[$list->tl_ID] = $list->tl_name;
		}

		return $data;
	}

}
