<?php
class CGUController extends template{
	public function CGUAction(){
		$v = new view();
		$this->assignConnectedProperties($v);

		// $v->assign("css", "noCompetition");
		// $v->assign("js", "noCompetition");
		$v->assign("title", "Conditions Générales");
		$settingManager = new settingManager();
		$setting = $settingManager->getSetting();
		$v->assign("setting", $setting);

        $v->setView("CGU");
    }
}