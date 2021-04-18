<?php 
	/*
		Gerer les heures
	*/
class HeuresManager{
	private $_bd;

	public function __construct(){
		$this->setBD(BDFactory::getMysqlConnectionWithPDO());
	}

	public function setBD(PDO $bd){
		$this->_bd = $bd;
	}
	
	public function lineExist($idEmploye, $date, $moment = ""){
		if(is_int($idEmploye)){
			$select = $this->_bd->query("SELECT * FROM heures WHERE idEmploye = ".$idEmploye." AND date_ = '".$date."'");
			if($moment != "")
				$select = $this->_bd->query("SELECT * FROM heures WHERE idEmploye = ".$idEmploye." AND date_ = '".$date."' AND moment = '".$moment."'");
			return ($select->rowCount() > 0);
		}else{
			trigger_error("L'id de l'employe doit etre un entier", E_USER_WARNING);
			return false;
		}
	}
	public function existOnMonth($idEmploye, $month, $year){
		if(is_int($idEmploye)){
			$select = $this->_bd->query("SELECT * FROM heures WHERE idEmploye = ".$idEmploye." AND date_ LIKE '".$month."-".$year."'");
			return ($select->rowCount() > 0);
		}else{
			trigger_error("L'id de l'employe doit etre un entier", E_USER_WARNING);
			return false;
		}
	}
	public function getLine($idEmploye, $date, $moment = ""){
		if($moment != ""){
			$select = $this->_bd->query("SELECT * FROM heures WHERE idEmploye = ".$idEmploye." AND date_ = '".$date."' AND moment = '".$moment."'");
			return $select->fetch();
		}else{
			$select = $this->_bd->query("SELECT * FROM heures WHERE idEmploye = ".$idEmploye." AND date_ = '".$date."'");
			$heures = array();
			while($heure = $select->fetch()){
				$heures[] = $heure;
			}
			return $heures;
		}
	}
	public function getOnMonth($idEmploye, $month, $year){
		if($this->existOnMonth($idEmploye, $month, $year)){
			$select = $this->_bd->query("SELECT * FROM heures WHERE idEmploye = $idEmploye AND date_ LIKE '".$month."-".$year."'");
			$heures = array();
			while($heure = $select->fetch()){
				$heures[] = $heure;
			}
			return $heures;
		}else{
			trigger_error("Erreur : Aucun enregistrement dans ce mois", E_USER_WARNING);
		}
	}
	public function add($idEmploye, $date, $moment, $heure){
		if(!$this->lineExist($idEmploye, $date, $moment)){
			$insertion = $this->_bd->prepare("INSERT INTO heures(idEmploye, date_, moment, heure) VALUES(?,?,?,?)");
			if($insertion->execute(array($idEmploye, $date, $moment, $heure)))
				return true;
			else 
				return false;
		}else{
			trigger_error("Un enregistrement pareil existe déjà", E_USER_WARNING);
			return false;
		}
	}
	public function update(){
		
	}
	public function deleteLine($idEmploye, $date, $moment = ""){
		if(!$this->lineExist($idEmploye, $date, $moment)){
			$endQuery = "";
			if($moment != "")
				$endQuery = "AND moment = '$moment'";
			return $this->_bd->query("DELETE FROM employes WHERE idEmploye = $idEmploye AND date_ = '$date' $endQuery");
		}else{
			trigger_error("Suppression impossible car l'enregistrement n'existe pas dans la basse de données", E_USER_WARNING);
		}
	}
	public function deleteOnMonth($idEmploye, $month, $year){
		if($this->existOnMonth($idEmploye, $month, $year)){
			return $this->_bd->query("DELETE FROM employes WHERE idEmploye = $idEmploye AND date_ LIKE '".$month."-".$year."'");
		}else{
			trigger_error("Suppression impossible car l'enregistrement n'existe pas dans la basse de données", E_USER_WARNING);
		}
	}
}

?>