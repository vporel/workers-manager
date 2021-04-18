<?php
class CongesManager{
	private $_bd;

	public function __construct(){
		$this->setBD(BDFactory::getMysqlConnectionWithPDO());
	}

	public function setBD(PDO $bd){
		$this->_bd = $bd;
	}
	public function lineExist($idEmploye, DateSys $dateDebut, DateSys $dateFin){
		if(is_int($idEmploye)){
			$select = $this->_bd->query("SELECT * FROM conges WHERE id_employe = ".$idEmploye." AND date_debut = '".$dateDebut->getDate()."' AND date_fin = '".$dateFin->getDate()."'");
			return ($select->rowCount() > 0);
		}else{
			trigger_error("L'id de l'employe doit etre un entier", E_USER_WARNING);
			return false;
		}
	}
	public function getConges($idEmploye, DateSys $dateDebut, DateSys $dateFin){
		$conges = array();
		$mois_inset = array($dateDebut->getMois());
		if($dateDebut->getAnnee() == $dateFin->getAnnee()){
			for($i = ($dateDebut->getMois()+1);$i<$dateFin->getMois();$i++){
				$mois_inset[] = $i;
			}
			for($i = 0;$i<count($mois_inset);$i++){
				// $c_mois = $current_mois
				$c_mois = $mois_inset[$i];
				$selectByMois = $this->_bd->query("SELECT * FROM conges WHERE id_employe = $idEmploye AND date_debut LIKE '%$c_mois-".$dateDebut->getAnnee()."'");
				if($selectByMois->rowCount() > 0){
					while($conge = $selectByMois->fetch()){
						$conges[] = $conge;
					}
				}
			}
		}
		return $conges;
	}
	public function add($idEmploye, DateSys $dateDebut, DateSys $dateFin){
		$calendar = new Calendar($dateDebut, $dateFin);
		$nbJours = count($calendar->getDatesNotWeekends());
		$requete = $this->_bd->prepare("INSERT INTO conges (id_employe, date_debut, date_fin, nb_jours) VALUES(?, ?, ?, ?)");
		return $requete->execute(array($idEmploye, $dateDebut->getDate(), $dateFin->getDate(), $nbJours));
	}
}
?>