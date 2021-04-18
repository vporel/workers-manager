<?php
/*
	Classe qui prend deux dates et retourne les jours , 
	les weekends, les jours en dehors des week-ends, etc
*/
	class Calendar{
		protected
			$dateDebut, $dateFin;
		public function __construct(DateSys $dateDebut, DateSys $dateFin){
			if($dateFin->getAnnee() <= $dateDebut->getAnnee()+1 
				AND $dateFin->getAnnee() >= $dateDebut->getAnnee()){
				$this->setDateDebut($dateDebut);
				$this->setDateFin($dateFin);
			}else{
				trigger_error("Calendar::_construct, Les années doivent être les mêmes Ou avoir une différence d'un", E_USER_WARNING);
			}
		}

		// Getters
		public function getDateDebut(){return $this->dateDebut;}
		public function getDateFin(){return $this->dateFin;}

		//SETTERS
		public function setDateDebut(DateSys $dateDebut){
			$this->dateDebut = $dateDebut;
		}
		public function setDateFin(DateSys $dateFin){
			$this->dateFin = $dateFin;
		}

		/*
			Retourne tous les dates de la période
			@param void
			return $dates:array:dateSys
		*/
		public function getDates(){
			$dateDebut = $this->dateDebut;
			$dateFin = $this->dateFin;
			$mois_all = array();
			$dates = array();
			if($dateDebut->getAnnee() == $dateFin->getAnnee()){
				$j = 1;
				for($i = $dateDebut->getMois();$i<=$dateFin->getMois();$i++){
					$mois_all[$j."-".$dateDebut->getAnnee()] = $i;
					$j++;
				}
			}else{
				$j = 1;
				for($i = $dateDebut->getMois();$i<=12;$i++){
					$mois_all[$j."-".$dateDebut->getAnnee()] = $i;
					$j++;
				}
				$j = 1;
				for($i = 1;$i<=$dateFin->getMois();$i++){
					$mois_all[$j."-".$dateFin->getAnnee()] = $i;
					$j++;
				}
			}
			foreach ($mois_all as $forAnnee => $mois) {
				$annee = (int) substr($forAnnee, -4);
				$virtual_date = new DateSys(1, $mois, $annee);
				$nbJours = $virtual_date->getNbJoursMois();
				$debutCompte = 1;
				$finCompte = $nbJours;
				if($annee = $dateDebut->getAnnee() AND $mois == $dateDebut->getMois()){
					$debutCompte = $dateDebut->getJour();
				}elseif($annee = $dateFin->getAnnee() AND $mois == $dateFin->getMois()){
					$finCompte = $dateFin->getJour();
				}
				for($i=$debutCompte;$i<=$finCompte;$i++){
					$dates[] = new DateSys($i, $mois,$annee);
				}
			}
			return $dates;
		}
		/*
			Retourne les dates qui ne sont pas des week-ends
			@param void
			@return $dates:array:DateSys
		*/
		public function getDatesNotWeekends(){
			$all_dates = $this->getDates();
			$dates = array();
			for($i=0;$i<count($all_dates);$i++){
				if(!$all_dates[$i]->isInWeekend()){
					$dates[] = $all_dates[$i];
				}
			}
			return $dates;
		}
	}
?>