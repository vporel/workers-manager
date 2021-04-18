<?php
	class EmployePeriode extends Employe{
		protected
			$dateDebut = "", $dateFin = "";
		public function __construct(array $donnees){
			$this->hydrate($donnees);
			$this->type = strtolower(get_class($this));
		} 
		/*
			GETTERS
		*/
		public function getDateDebut($param = ""){ 
			if($param == "obj")
				return $this->dateDebut;
			return $this->dateDebut->getDate();
		}
		public function getDateFin($param = ""){ 
			if($param == "obj")
				return $this->dateFin;
			return $this->dateFin->getDate();
		}
		
		/*
			SETTERS
		*/
		public function setDateDebut(DateSys $dateDebut){
			$this->dateDebut = $dateDebut;
		}
		public function setDateFin(DateSys $dateFin){
			$this->dateFin = $dateFin;
		}
	}
?>