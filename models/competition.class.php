<?php 
class competition extends motherClass{

	protected $id_competition=null;
	protected $name=null;
	protected $description=null;
	protected $start_date=1;
	protected $end_date=null;
	protected $prize=null;
	protected $id_winner=null;
	protected $active=null;
	protected $url_prize=null;
	protected $date_created=null;
	protected $date_updated=null;
	protected $deleted=null;
		
	protected $totalParticipants=null;
	protected $last_name=null;
	protected $first_name=null;
	protected $email=null;
	protected $location=null;
	protected $age_range=null;




	public function setId_competition($v){$this->id_competition=$v;}
	public function setName($v){$this->name=$v;}
	public function setDescription($v){$this->description=$v;}
	public function setStart_date($v, $format=0){
		if($format==0)
			$this->start_date=$v;
		else
			$this->start_date = date('Y-m-d', strtotime(str_replace('/', '-', $v)));
	}
	public function setEnd_date($v, $format=0){
		if($format==0)
			$this->end_date=$v;
		else
			$this->end_date = date('Y-m-d', strtotime(str_replace('/', '-', $v)));
	}
	public function setPrize($v){$this->prize=$v;}
	public function setId_winner($v){$this->id_winner=$v;}
	public function setActive($v){
		if($v=="on" || $v=="1")
			$this->active = 1; 
		else if($v=="2")
			$this->active = 2;
		else
			$this->active = 0;
	}
	public function setUrl_prize($v){$this->url_prize=$v;}
	public function setDate_created($v){}
	public function setDate_updated($v){$this->date_updated=$v;}
	public function setDeleted($v){$this->deleted=$v;}

	public function setTotalParticipants($v){$this->totalParticipants=$v;}

	public function setLast_name($v){$this->last_name=$v;}	
	public function setFirst_name($v){$this->first_name=$v;}	
	public function setAge_range_name($v){$this->age_range=$v;}	
	public function setEmail($v){$this->email=$v;}	
	public function setLocation($v){$this->location=$v;}


	public function getId_competition(){return $this->id_competition;}
	public function getName(){return $this->name;}
	public function getDescription(){return $this->description;}
	public function getStart_date(){return str_replace("-","/",$this->start_date);}
	public function getEnd_date(){return str_replace("-","/",$this->end_date);}
	public function getPrize(){return $this->prize;}
	public function getId_winner(){return $this->id_winner;}
	public function getActive(){return $this->active;}
	public function getUrl_prize(){return $this->url_prize;}
	public function getDate_created(){return $this->date_created;}
	public function getDate_updated(){return $this->date_updated;}
	public function getDeleted(){return $this->deleted;}
	
	public function getTotalParticipants(){return $this->totalParticipants;}

	public function getLast_name(){return $this->last_name;}	
	public function getFirst_name(){return $this->first_name;}	
	public function getAge_range(){return $this->age_range;}	
	public function getEmail(){return $this->email;}	
	public function getLocation(){return $this->location;}
}