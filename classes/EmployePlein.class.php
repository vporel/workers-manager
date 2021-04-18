<?php
	class EmployePlein extends Employe{
		protected
			$dateNaissance = "", $dateEmbauche = "",$quotite = "", $joursConges = "", 
			$congesUtilises = "";
		public function __construct(array $donnees){
			$this->hydrate($donnees);
			$this->type = strtolower(get_class($this));
		} 
		/*
			GETTERS
		*/
		public function getDateNaissance(){ return $this->dateNaissance->getDate();}
		public function getDateEmbauche(){ return $this->dateEmbauche->getDate();}
		public function getQuotite(){ return $this->quotite;}
		public function getJoursConges(){return $this->joursConges;}
		public function getCongesUtilises(){return $this->congesUtilises;}
		public function getCongesRestants(){return $this->joursConges - $this->congesUtilises;}

		public function getAge(){
			return ((int) date('Y')) - $this->dateNaissance->getAnnee();
		}
		/*
			SETTERS
		*/
		public function setDateNaissance(DateSys $dateNaissance){
			$this->dateNaissance = $dateNaissance;
		}
		public function setDateEmbauche(DateSys $dateEmbauche){
			$this->dateEmbauche = $dateEmbauche;
		}
		public function setQuotite($quotite){
			$quotite = (int) $quotite;
			if(is_int($quotite)){
				$this->quotite = $quotite;
			}else{
				trigger_error("La quotité doit etre un entier strictement positif", E_USER_WARNING);
			}
		}
		public function setJoursConges($nbJours){
			$nbJours = (int) $nbJours;
			if($nbJours > 0){
				$this->joursConges = $nbJours;
			}else{
				trigger_error("Jours congés : Le nombre de jour doit etre un entier positif supérieur à 0", E_USER_WARNING); 
			}
		}
		public function setCongesUtilises($nbJours){
			$nbJours = (int) $nbJours;
			if(is_int($nbJours)){
				$this->congesUtilises = $nbJours;
			}else{
				trigger_error("Congés utilisés : Le nombre de jour doit etre un entier positif supérieur à 0", E_USER_WARNING); 
			}
		}
	}
?>