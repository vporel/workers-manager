<?php
	/*
		Gerer les employes
	*/
if(!function_exists("concat_array")){
	function concat_array($tab1, $tab2){
		for($i = 0;$i<count($tab2);$i++){
			$tab1[] = $tab2[$i];
		}
		return $tab1;
	}
}
class EmployesManager{
	private $_bd;

	public function __construct(){
		$this->setBD(BDFactory::getMysqlConnectionWithPDO());
	}

	public function setBD(PDO $bd){
		$this->_bd = $bd;
	}
	public static function getAttributsEmploye($type){
		$attrs = array("id", "nom", "prenom", "sexe", "fonction", "service", "salaire", "heureArrivee", "heureDepart", "type");
		$others = array();
		if($type == "employeperiode"){
			$others = array("dateDebut", "dateFin");
		}else if($type == "employeplein"){
			$others = array("dateNaissance", "dateEmbauche", "quotite", "joursConges");
		}
		return concat_array($attrs, $others);
	}
	public function employeExist($id){
		if($id > 0){
			$select = $this->_bd->query("SELECT * FROM employes WHERE id = ".$id);
			return ($select->rowCount() > 0);
		}else{
			trigger_error("L'id en paramètre n'est pas un entier", E_USER_WARNING);
		}
	}
	public function nameExist($nom, $prenom){
		if($nom != "" AND $prenom != ""){
			$select = $this->_bd->query("SELECT * FROM employes WHERE nom = '".$nom."' AND prenom = '".$prenom."'");
			return ($select->rowCount() > 0);
		}else{
			trigger_error("Le nom ou le prénom est vide", E_USER_WARNING);
			 
		}
	}
	public function getEmploye($id){
		$select = $this->_bd->query("SELECT * FROM employes WHERE id = ".$id);
		$employe = $select->fetch(PDO::FETCH_ASSOC);
		$employe['id'] = (int) $employe['id'];
		$heureArrivee = explode(' H ', $employe['heureArrivee']);
		$heureDepart = explode(' H ', $employe['heureDepart']);
		$heureArrivee = new HeureSys((int) $heureArrivee[0],(int) $heureArrivee[1]);
		$heureDepart = new HeureSys((int) $heureDepart[0],(int) $heureDepart[1]);
		$employe['heureArrivee'] = $heureArrivee;
		$employe['heureDepart'] = $heureDepart;
		if($employe['type'] == "employeperiode"){
			$employe['dateDebut'] = new DateSys($employe['dateDebut']);
			$employe['dateFin'] = new DateSys($employe['dateFin']);
			return new EmployePeriode($employe);
		}elseif($employe['type'] == "employeplein"){
			$employe['dateNaissance'] = new DateSys($employe['dateNaissance']);
			$employe['dateEmbauche'] = new DateSys($employe['dateEmbauche']);
			return new EmployePlein($employe);
		}
	}
	public function getList($type, $by = ""){
		$select = $this->_bd->query("SELECT * FROM employes WHERE type = '$type' ORDER BY nom");
		$employes = array();
		while($employe = $select->fetch(PDO::FETCH_ASSOC)){
			if($by == ""){
				if($type == "employeperiode")
					$employes[] = $this->getEmploye((int) $employe['id']);
				elseif($type == "employeplein")
					$employes[] = $this->getEmploye((int) $employe['id']);
			}else{
				switch ($by) {
					case 'noms': $employes[] = $employe['nom']." ".$employe['prenom'];break;
					case 'ids': $employes[] = $employe['id'];break;
				}
			}
		}
		return $employes;
	}
	public function add(Employe $employe){
		$attrs_ = self::getAttributsEmploye($employe->getType());
		$attrs = array();
		$values = array();
		$pointsInterrogation = "";
		for($i=0;$i<count($attrs_);$i++){
			$method = 'get'.ucfirst($attrs_[$i]);
			if($employe->$method() != ""){
				$attrs[] = $attrs_[$i];
				$values[] = $employe->$method();
				$pointsInterrogation .= "?";
				if($i < (count($attrs_)-1))
					$pointsInterrogation .= ",";
			}
		}
		$attrs = implode($attrs, ',');
		$insertion = $this->_bd->prepare("INSERT INTO employes($attrs) VALUES($pointsInterrogation)");
		if($insertion->execute($values))
			return array(true);
		else 
			return array(false);
	}
	public function update(Employe $employe){
		$changements = "";
		$attrs = self::getAttributsEmploye($employe->getType());
		for($i=0;$i<count($attrs);$i++){
			$method = 'get'.ucfirst($attrs[$i]);
			if($employe->$method() != ""){
				$changements .= $attrs[$i]." = '".$employe->$method()."'";
				if($i < (count($attrs)-1))
					$changements .= ",";
			}
		}
		return $this->_bd->query("UPDATE employes SET ".$changements." WHERE id = ".$employe->getId());
	}
	public function delete(Employe $employe){
		return $this->_bd->query("DELETE FROM employes WHERE id = ".$employe->getId());
	}
}

?>