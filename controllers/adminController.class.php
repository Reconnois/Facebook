<?php

class adminController extends template{
	public function adminAction(){
		$v = new view();
		$this->assignConnectedProperties($v);
		$v->assign("css", "admin");
		$v->assign("js", "admin");				
		
		//Competitions
		$competitionManager = new competitionManager();
		$listCompetitions = $competitionManager->getAllCompetitions();
		$v->assign("listCompetitions",$listCompetitions);
		
		//Utilisateurs

		if($this->competition!==NULL){
			$participateManager = new participateManager();
			$listUsers = $participateManager->getParticipantsByCompetition($this->competition);
			$v->assign("listUsers",$listUsers);
		}

		//Participations
		$participateManager = new participateManager();
		$listParticipants = $participateManager->getAllParticipants();
		$v->assign("listParticipants",$listParticipants);

		//Photo signalées
		$listReportedPhoto = $participateManager->getPhotoReported();
		$v->assign("listReportedPhoto",$listReportedPhoto);

		$designManager = new designManager();
		$listActiveDesign = $designManager->getActiveDesign();
		$v->assign("listActiveDesign", $listActiveDesign);

		if($this->isAdmin==1)
			$v->setView("admin","templateadmin");
		else
			header('Location: '.WEBPATH);
	}

	//ADMINISTRATION DES COMPETITIONS

	public function addCompetitionAction(){
		$error = "";
		$check = false;
		if(!$_POST){
			$error .= "Erreur !";	
		}

		if($error==""){
			$error = "ok";
			$competition = new competition($_POST);
			//Verification du bon format de la date
			$competition->setStart_date($_POST['start_date'],1);
			$competition->setEnd_date($_POST['end_date'],1);
			$competitionManager = new competitionManager();
			
			if(isset($_POST['active'])){
				$competition->setActive($_POST['active']);
				$check = $competitionManager->timeCompetition($competition);
			}
			else 
				$competition->setActive("0");

			if(!$check)
				$competitionManager->insertCompetition($competition);
			else
				$error="Un concours est déjà organisé dans ce laps de temps.";
		}

		echo $error;
	}

	public function selectWinnerAction(){
		//Selection manuelle du gagnant
		$infosParticipation = [
			'id_competition' => $this->competition->getId_competition(),
			'id_user' => $_POST['id_user']
		];
		$participation = new participate($infosParticipation);
		$participationManager = new participateManager();
		$result = $participationManager->getParticipationByIds($participation);
		if($result!=NULL && $this->competition!=NULL){
			$competitionManager = new competitionManager();
			$competition = $competitionManager->getCompetitionById($this->competition->getId_competition());
			$competition->setId_winner($result->getId_user());
			$competition->setActive(2);
			$competitionManager->updateCompetition($competition);

			$this->checkWinner();
			
		}
	}

	public function editCompetitionAction(){
		$error = "";
		if(!$_POST){
			$error .= "Erreur !";	
		}

		if($error==""){
			$error = "ok";	
			$check=false;
			$competition = new competition($_POST);
			$competition->setStart_date($_POST['start_date'],1);
			$competition->setEnd_date($_POST['end_date'],1);
			$competitionManager = new competitionManager();
			$table = [
				"start_date" => $_POST['start_date'],
				"end_date" => $_POST['end_date']
			];

			if(isset($_POST['active'])){
				$competition->setActive($_POST['active']);
				$check = $competitionManager->timeCompetition($table);
			}
			else 
				$competition->setActive("0");

			if(!$check)
				$upd = $competitionManager->updateCompetition($competition);
			else
				$error="Un concours est déjà organisé dans ce laps de temps.";				
		}
		echo $error;
	}

	public function searchUserAction(){
		if(trim($_POST['user'])!=""){
			$userManager = new userManager();
			$listUser = $userManager->getUsersByName($_POST['user']);
			echo json_encode($listUser);
		}
	}

	//ADMINISTRATION DES PHOTOS
	public function adminPhotoAction(){
		$participate = new participate($_POST);
		$participateManager = new participateManager();
		$participateManager->editPhoto($participate);	
	}	
}

