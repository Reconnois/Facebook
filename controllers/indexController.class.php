<?php
class indexController extends template{
	public function indexAction(){
		$v = new view();
		$this->assignConnectedProperties($v);
		$v->assign("css", "index");
		$v->assign("js", "index");				
		
		//Ajout d'un lien "connexion à Facebook" sur cette page
		$this->login($v);

		/*Etablir une fonction (dans la classe mère ou fille)
		pour gérer à chaque load d'une page la validité de la session utilisateur
		(s'il n'a pas supprimé des permission entre-temps)*/

		if(isset($_SESSION['ACCESS_TOKEN'])){
			//Liste admins
			$v->assign("admins", $this->dataApi(TRUE,'/app/roles',array(),"1804945786451180|yqj6xWNaG2lUvVv3sfwwRbU5Sjk"));
			
			//Infos de l'utilisateur
			$infosUser = ['id','name','first_name','last_name','email','birthday','location'];
			$v->assign("user", $this->dataApi(TRUE,'/me?fields=',$infosUser,"",FALSE));

			//Récupération des différentes photos de l'utilisateur
			$infoPhoto = "photos{id,name,source},albums{name,photos{id,name,source}}";
			$v->assign("images", $this->dataApi(TRUE,'/me?fields=',$infoPhoto,""));
		}
		$v->setView("index","templateempty");
	}


	public function submitAction(){
		/* --ENVOI DE DONNEES PAR L'UTILISATEUR DEPUIS L'ACCUEIL -- */
		if(isset($_POST['uploadFile'])){
			
			$albumCompetition = $this->searchAlbumCompetition();

			//Envoi d'une image depuis l'ordi
			if(isset($_FILES['file'])){
				//Déplacement de l'image dans le serveur
				$target = __ROOT__.'/web/uploads/' . basename( $_FILES['file']['name']) ; 
		        move_uploaded_file($_FILES['file']['tmp_name'], $target); 
		        $image['image'] = __ROOT__."/web/uploads/".$_FILES['file']['name'];

				$data = [
				  'message' => 'My awesome photo upload example.',
				  'source' => $this->fb->fileToUpload($image['image']),
				];

				//Envoi de la photo sur Facebook
				$idFbPhoto = $this->dataApi(FALSE,'/'.$albumCompetition,"/photos",$data);
				unlink($image['image']);
				
				$infosPhoto = $this->dataApi(TRUE,'/'.$idFbPhoto['id'].'?fields=','images',"");
			}

			//Enregistrement des infos de l'utilisateur
			$listInfosUser = ['id','name','first_name','last_name','email','birthday','location'];
			$infosUser = $this->dataApi(TRUE,'/me?fields=',$listInfosUser,"");
			
			$infosUser['location'] = $infosUser['location']['name'];
			$infosUser['idFacebook'] = $infosUser['id'];
			unset($infosUser['id']);

			$user = new user($infosUser);
			$userManager = new userManager();
			$user = $userManager->saveUser($user);

			//Enregistrement de la participation
			$infosParticipation =[
			  	'id_competition' => $this->competition->getId_competition(),
				'id_user' => $user->getId_user(),
				'id_photo' => $infosPhoto['id'],
				'url_photo' => $infosPhoto['images'][0]['source']
			];

			$participation = new participate($infosParticipation);
			$participationManager = new participateManager();
			$participationManager->saveParticipation($participation);
		}
		header('Location: '.WEBPATH);
	}

	private function searchAlbumCompetition(){
		$albums = $this->dataApi(TRUE,'/me','/albums',"");
		
		foreach ($albums['data'] as $key => $album) {
			if($album['name']=="Concours Pardon-Maman"){
				$albumCompetition = $album['id'];
				break;
			}
		}
		
		//Création de l'album du concours si absent chez l'utilisateur
		if(!isset($albumCompetition)){
			$infos = [
				'name' => 'Concours Pardon-Maman',
				'privacy' => json_encode(array('value'=>'SELF'))
			];
			$graphNode = $this->dataApi(FALSE,"/me","/albums",$infos);
			$albumCompetition = $graphNode['id'];
		}
		
		return $albumCompetition;
	}

}

