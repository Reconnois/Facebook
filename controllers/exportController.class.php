<?php

class exportController extends template{
    public function exportAction(){
        $v = new view();
        $this->assignConnectedProperties($v);
        $v->assign("css", "admin");
        $v->assign("js", "admin");
        $v->setView("/admin/export","templateadmin");
    }

    public function exportCompetitionAction() {
        $competitionManager = new competitionManager();
        $listCompetitions = $competitionManager->getAllCompetitions();
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=Export_Concours_' . date("dmY"). '.csv');
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
        $entetes = array(
            '#ID', 
            'Nom du concours',
            'Description',
            'Date de début du concours',
            'Date de fin du concours', 
            'Prix du gagnant',
            'ID du gagnant',  
            'Statut du concours',
            'URL du prix',
            'Nombre de participants');

        fputcsv($output, $entetes, ";");
        foreach ($listCompetitions as $key => $competition) {
            fputcsv($output, (array)$competition, ";");
        }
        fclose($output);
    }

    public function exportCompetitionXMLAction(){
        $competitionManager = new competitionManager();
        $listCompetitions = $competitionManager->getAllCompetitions();
        $xml = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"utf-8\" ?><Competitions></Competitions>");

        foreach ($listCompetitions as $key => $competition){
            $compet = $xml->addChild('competition');
            $compet->addChild('id', $competition->getId_competition());
            $compet->addChild('nom', $competition->getName());
            $compet->addChild('description', $competition->getDescription());
            $compet->addChild('datedeb', $competition->getStart_date());
            $compet->addChild('datefin', $competition->getEnd_date());
            $compet->addChild('prix', $competition->getPrize());
            $compet->addChild('id_gagnant', $competition->getId_winner());
            $compet->addChild('statut', $competition->getActive());
            $compet->addChild('url_prix', $competition->getUrl_prize());
        }
        header('Content-type: text/xml');
        print($xml->asXML());
    }

    public function exportUserAction() {
        $userManager = new userManager();
        $listUsers = $userManager->getAllUsers();
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=Export_Participants_' . date("dmY"). '.csv');
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
        $entetes = array(
            'ID', 
            'Nom du participant',
            'Prénom du participant',
            'Email',
            'Date d\'anniversaire', 
            'Validation des CGU',
            'Statut du participant');

        fputcsv($output, $entetes, ";");
        foreach ($listUsers as $key => $user) {
            fputcsv($output, (array)$user, ";");
        }
        fclose($output);
    }

    public function exportUserXMLAction(){
        $userManager = new userManager();
        $listUsers = $userManager->getAllUsers();
        $xml = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"utf-8\" ?><Participants></Participants>");

        foreach ($listUsers as $key => $user){
            $compet = $xml->addChild('participant');
            $compet->addChild('id', $user->getId_user());
            $compet->addChild('nom', $user->getLast_name());
            $compet->addChild('prenom', $user->getFirst_name());
            $compet->addChild('email', $user->getEmail());
            $compet->addChild('date_anniversaire', $user->getBirthday());
            $compet->addChild('cgu', $user->getValidation_cgu());
            $compet->addChild('statut', $user->getStatus());
        }
        header('Content-type: text/xml');
        print($xml->asXML());
    }
}