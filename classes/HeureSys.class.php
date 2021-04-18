<?php
class HeureSys{
	protected $heure, $minutes, $secondes;

	public function __construct($heure = 0, $minutes = 0, $secondes = 0){
		if(is_string($heure) AND $minutes == 0 AND $secondes == 0){
			$this->setHeureComplet($heure);
		}else{
			$this->setHeure($heure);
			$this->setMinutes($minutes);
			$this->setSecondes($secondes);
		}
	}
	// GETTERS
	public function getHeure(){return $this->heure;}
	public function getMinutes(){return $this->minutes;}
	public function getSecondes(){return $this->secondes;}
	//SETTERS
	public function setHeure($heure){
		if(is_int($heure) AND $heure >= 0){
			$this->heure = $heure;
		}else{
			trigger_error("L'heure doit etre un entier strictement positif inférieur ou égal à 24", E_USER_WARNING);
		}
	}
	public function setMinutes($minutes){
		if(is_int($minutes) AND $minutes >= 0 AND $minutes <=59){
			$this->minutes = $minutes;
		}else{
			trigger_error("La minute doit etre un entier  positif inférieur ou égal à 59", E_USER_WARNING);
		}
	}
	public function setSecondes($secondes){
		if(is_int($secondes) AND ($secondes < 59)){
			$this->secondes = $secondes;
		}else{
			trigger_error("La seconde doit etre un entier  positif inférieur ou égal à 59", E_USER_WARNING);
		}
	}
	// Autres fonctions
	public function setHeureComplet($heure){
		$elmts = explode(' H ', $heure);
		if(count($elmts) == 2){
			$this->setHeure((int) $elmts[0]);
			$this->setMinutes((int) $elmts[1]);
			$this->setSecondes(0);
		}else{
			trigger_error("HeureSys::setHeureComplet : L'heure' est incorrect: Format (hh H mm)", E_USER_WARNING);
		}
	}
	public function getHeureComplet(){ 
		$heure = "";
		if($this->heure < 10) 
			$heure .= "0".$this->heure;
		else 
			$heure .= $this->heure;
		$heure .= " H ";
		if($this->minutes < 10) 
			$heure .= "0".$this->minutes;
		else 
			$heure .= $this->minutes;
		if($this->secondes > 0){
			$heure .= " min ".$this->secondes." sec";
		}
		return $heure;

	}
	/*
		OPERATEURS
	*/
	public function plus(HeureSys $toAdd){
		$h = $this->heure + $toAdd->getHeure();
		$m = $this->minutes + $toAdd->getMinutes();
		$s = $this->secondes + $toAdd->getSecondes();
		if($s > 59){ $m += (int) ($s/60); $s = $s%60;}
				if($m > 59){$h += (int) ($m/60);$m = $m%60;}
		$this->setHeure($h);
		$this->setMinutes($m);
		$this->setSecondes($s);
	}
	public function moins(HeureSys $toRemove){
		$h = $this->heure - $toRemove->getHeure();
		$m = $this->minutes - $toRemove->getMinutes();
		$s = $this->secondes - $toRemove->getSecondes();
		if($h >= 0){
			if($m >= 0){
				if($s >= 0){
					if($s > 59){ $m += (int) ($s/60); $s = $s%60;}
					if($m > 59){$h += (int) ($m/60);$m = $m%60;}
				}else{
					if($m > 0){
						$m -= 1;
						$s = 60 - $toRemove->getSecondes() + $this->secondes;
					}else{
						trigger_error("L'heure à soustraire est plus grande",E_USER_WARNING);
					}
				}
			}else{
				if($h > 0){
					$h -= 1;
					$m = 60 - $toRemove->getMinutes() + $this->minutes;
				}else{
					trigger_error("L'heure à soustraire est plus grande",E_USER_WARNING);
				}
			}
		}else{
			trigger_error("L'heure à soustraire est plus grande", E_USER_WARNING);
		}
		$this->setHeure($h);
		$this->setMinutes($m);
		$this->setSecondes($s);
	}
}
?>