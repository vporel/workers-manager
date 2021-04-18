<?php 
class DateSys{
	protected $jour, $mois, $annee, $time_date;
	public static $mois_array = array("Janvier", "Fevrier", "Mars", "Avril", "Mai", "Juin", "Juillet",
		"Août", "Septembre", "Octobre", "Novembre", "Decembre");

	public function __construct($jour, $mois = 0, $annee = 0){
		if($mois == 0 AND $annee == 0){
			$this->setDate($jour);
		}elseif($mois != 0 AND $annee == 0){
			trigger_error("DateSys::__construct : Erreur lors d'instanciation: renseignez l'année", E_USER_WARNING);
		}else{
			$this->setAnnee($annee);
			$this->setMois($mois);
			$this->setJour($jour);
		}
		$this->time_date = mktime(0,0,0, $this->mois, $this->jour, $this->annee);
	}
	// GETTERS
	public function getTime_date(){return $this->time_date;}
	public function getJour($param = ""){
		if($param == "l")
			return date('D', $this->time_date);
		elseif($param == "num"){
			$num = date('w', $this->time_date);
			if($num == 0) $num = 7;
			return $num;
		}
		return $this->jour;
	}
	public function getMois($param = ""){
		$mois_array = self::$mois_array;
		if($param == "l")
			return $mois_array[$this->mois-1];
		return $this->mois;
	}
	public function getAnnee(){return $this->annee;}
	//SETTERS
	public function setJour($jour){
		if(is_int($jour) AND $jour > 0 AND $jour <=31){
			if($this->mois == 2){
				if($jour <= 29){
					$this->jour = $jour;
				}else{
				trigger_error("Le mois dans cette date est le deuxieme donc on ne peut avoir un nombre de jours supérieur à 29",E_USER_WARNING);
				}
			}else if(in_array($this->mois, array(4,6,9,11))){
				if($jour <= 30){
					$this->jour = $jour;
				}else{
				trigger_error("Le mois dans cette date ne peut avoir un nombre de jours supérieur à 30",E_USER_WARNING);
				}
			}else{
				$this->jour = $jour;
			}
		}else{
			trigger_error("Le jour dans la date doit etre un entier strictement positif inférieur ou égal à 31", E_USER_WARNING);
		}
	}
	public function setMois($mois){
		if(is_int($mois) AND $mois > 0 AND $mois <=12){
			if(($mois == 2 AND $this->jour > 29) OR(in_array($mois, array(4,6,9,11)) AND $this->jour > 30)){
				trigger_error("Cette date a ".$this->jour." jours. Vous ne pouvez donc pas mettre le mois N° ".$mois, E_USER_WARNING);
			}else
				$this->mois = $mois;
		}else{
			trigger_error("Le mois dans la date doit etre un entier strictement positif inférieur ou égal à 12", E_USER_WARNING);
		}
	}
	public function setAnnee($annee){
		if(is_int($annee) AND ($annee > 1900)){
			$this->annee = $annee;
		}else{
			trigger_error("Annee incorrecte", E_USER_WARNING);
		}
	}
	// Autres finctions
	/*
		Prend une date et la décompose pour les attributs de l'objet
		@param $date:string
		@return void
	*/
	public function setDate($date){
		$elmts = explode('-', $date);
		if(count($elmts) == 3){
			$this->setJour((int) $elmts[0]);
			$this->setMois((int) $elmts[1]);
			$this->setAnnee((int) $elmts[2]);
		}else{
			trigger_error("DateSys::setDate : La date est incorrect: Format (jj-mm-aaaa)", E_USER_WARNING);
		}
	}
	/**
		Retourne la date complète
		@param $param:string
		@return $date
	*/
	public function getDate($param = ""){ 
		$date = "";
		if($this->jour < 10) $date .= "0".$this->jour;
		else $date .= $this->jour;
		if($param =="l"){
			$date .= " ".$this->getMois('l')." ";
		}else{
			$date .= "-";
			if($this->mois < 10) $date .= "0".$this->mois;
			else $date .= $this->mois;
				$date .= "-";
		}
		$date .= $this->annee;
		return $date;

	}
	/**
		Retourne le nombre de jour du mois de l'objet
		@param void
		@return ..
	*/
	public function getNbJoursMois(){
		return (int) date('d', mktime(0,0,0,($this->mois+1), -1, $this->annee));
	}
	/**
		Retourne true si le jour est un samedi ou un dimanche
		@param $param:string
		@return $date
	*/
	public function isInWeekend(){
		return ($this->getJour('num') == 6 OR $this->getJour('num') == 7);
	}

}
?>