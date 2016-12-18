<?php 
class motherClass{

	public function __construct(array $data){
		$this->hydrate($data);
	}

	protected function hydrate($data){
		foreach ($data as $key => $value) {
			$method = 'set'.ucfirst($key);
			if(method_exists($this, $method))
				$this->$method(trim($value));
		}
	}

	public function getAllAttributes(){
		//Retourne l'ensemble des attributs d'une classe
		return get_object_vars($this);
	}

	protected function multiexplode ($delimiters,$string) {
	    $ready = str_replace($delimiters, $delimiters[0], $string);
	    $launch = explode($delimiters[0], $ready);
	    return  $launch;
	}
}