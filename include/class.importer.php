<?php
/*
* @author Melih Onvural, Philipp Kiszka
* @package Collabtive
* @name Importer
* @version 0.5.5
* @link http://collabtive.o-dyn.de
* @license http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt (LGPLv2.1)
*
*/

class importer
{
	private $peopleHash;
	private $mylog;
	public $projectCount;
	public $peopleCount;
	public $taskCount;
	public $msgCount;
	public $milesCount;

	function __construct()
    {
		$this->mylog = new mylog;
		$this->peopleHash = array( );
		$this->projectCount  = 0;
		$this->peopleCount  = 0;
		$this->taskCount  = 0;
		$this->msgCount  = 0;
	}

	/*
	* Imports the result of a Basecamp Project Export file
	*
	* @param
	*/
	function importBasecampXmlFile($upload_file)
    {
		$file = simplexml_load_file($upload_file);

		if(!$file)
		{
			return false;
		}

		$this->addPeople($file->firm->people);
		$this->addProjects($file->projects);
	}

	private function addMessages($project_id, $messagesArray)
    {
		$addMessage = new message();
		$userObj = new user();

		foreach($messagesArray->{'post'} as $message)
        {
			$insid = 0;
            $title = $message->{'title'};
			$text = $message->{'body'};
			$uid = $message->{'author-id'};
			$user = $this->peopleHash["$uid"];
			$userProfile = $userObj->getProfile($user);
			$username = $userProfile["name"];
			$replies = $message->{'comments'};
            $insid = $addMessage->add($project_id, $title, $text, "", $user, $username, 0, 0);

            if($insid > 0)
			{
				++$this->msgCount;
				if(count($replies) > 0)
				{
					foreach($replies->{'comment'} as $reply)
					{
						++$this->msgCount;

						$replytext = $reply->{"body"};
						$ruid = $reply->{'author-id'};
						$ruser = $this->peopleHash["$ruid"];
						$ruserProfile = $userObj->getProfile($ruser);
						$rusername = $ruserProfile["name"];

						$addMessage->add($project_id, $replytext, $replytext, "", $ruser, $rusername, $insid, 0);
					}
				}
			}
		}
	}

	private function addMilestones($project_id, $milestonesArray)
    {
		$milestonesHash = array();
		$addMilestone = new milestone();

		foreach($milestonesArray->{'milestone'} as $milestone)
        {
			$name = $milestone->{'title'};
			$desc = $name;
			$end = $milestone->{'deadline'};

			$status = 1;
			if($milestone->{'completed'} == "completed")
			{
				$status = 0;
			}

			$mid = $addMilestone->add($project_id, $name, $desc, $end, $status);

			if ($mid) {
				$iid = "".$milestone->id;
				$milestonesHash[$iid] = $mid;
				++$this->milesCount;
			}
		}
		return $milestonesHash;
	}

	private function addPeople($peopleArray)
    {
		$user = new user();

		foreach($peopleArray->person as $person) {
			$company = 0; //note that this should be updated when company becomes a used object

			$isAdmin = 1;

            $rolesobj = new roles();

            $adminrid = $rolesobj->add("BasecampAdmin", array("add" => 1, "edit" => 1, "del" => 1 , "close" => 1), array("add" => 1, "edit" => 1, "del" => 1, "close" => 1), array("add" => 1, "edit" => 1, "del" => 1, "close" => 1), array("add" => 1, "edit" => 1, "del" => 1, "close" => 1), array("add" => 1, "edit" => 1, "del" => 1), array("add" => 1, "edit" => 1, "del" => 1, "read" => 1), array("add" => 0), array("add" => 1));

            $userrid = $rolesobj->add("BasecampUser", array("add" => 1, "edit" => 1, "del" => 0, "close" => 0), array("add" => 1, "edit" => 1, "del" => 0), array("add" => 1, "edit" => 1, "del" => 1), array("add" => 1, "edit" => 1, "del" => 1), array("add" => 1, "edit" => 1, "del" => 1), array("add" => 1, "edit" => 1, "del" => 1, "read" => 0), array("add" => 1) , array("add" => 0));


			$username = $person->{'user-name'};
			$email = $person->{'email-address'};
			$pass = $email;
			$uid = $user->add($username, $email, $company, $pass);
            if($uid)
            {
                if($person->{'administrator'} == "true")
			     {
			       $rolesobj->assign($adminrid, $uid);
			         $isAdmin = 5;
			     }
		      	elseif($person->{'client-id'} != 0)
		      	{
		      	   $rolesobj->assign($userrid, $uid);
			     	$isAdmin = 0;
		      	}
                $iid = "".$person->{'id'};
				$this->peopleHash[$iid] = $uid;
				++$this->peopleCount;
			}
		}
	}

	private function addProjects($projectsArray)
    {
		$addProject = new project();

		foreach($projectsArray->project as $project)
        {
			$name = $project->{'name'};
			$desc = $name;
			$start = $project->{'created-on'};

			//TODO das muss in nen loop mit assign, damit wirklich alle user assigned werden
			$uid = $project->{'participants'}->{'person'};
			$user = $this->peopleHash["$uid"];

			$status = 1;
			if($project->{'status'} != "active")
			{
				$status = 0;
			}

			$project_id = $addProject->AddFromBasecamp($name, $desc, $start, $status);

			if($project_id)
            {
				$addProject->assign($user,$project_id);
				if(isset($_SESSION["userid"]))
				{
				    $addProject->assign($_SESSION["userid"],$project_id);
				}
                $milestonesHash = $this->addMilestones($project_id, $project->{'milestones'});
				$this->addTasks($project_id, $milestonesHash, $project->{'todo-lists'});
				$this->addMessages($project_id, $project->{'posts'});
				++$this->projectCount;
			}
		}
	}

	private function addTasks($project_id, $milestonesHash, $taskListArray)
    {
		$addTaskList = new tasklist();
		$addTask = new task();

		foreach($taskListArray->{'todo-list'} as $taskList)
        {

			$name = $taskList->{'name'};

			if(isset($milestonesHash))
            {
				$mid = $taskListArray->{'milestone-id'};
				if(strlen($mid) > 0)
				{
					$milestoneId = $milestonesHash["$mid"];
				}
				else
				{
					$milestoneId = 0;
				}
			}

			$desc = $taskListArray->{'description'};
			$access = 0;
			$tasklistId = $addTaskList->add_liste($project_id, $name, $desc, $access, $milestoneId);

			foreach($taskList->{'todo-items'}->{'todo-item'} as $todo)
            {
				//$end = strtotime("+1 month", strtotime($todo->{'created-on'}));
				$end = strtotime($todo->{'created-on'} . " +1month");
				$title = $todo->{'name'};
				$text = $todo->{'content'};
				if(!$title)
				{
					$title = $text;
				}
				$uid = $todo->{'responsible-party-id'};
				$assigned = $this->peopleHash["$uid"];

				//class.task::add() needed to be modified to pass a timestamp directly.
				if($addTask->add($end, $title, $text, $tasklistId, $assigned, $project_id))
				{
					++$this->taskCount;
				}
			}
		}
	}
}
?>