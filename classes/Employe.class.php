<?php
abstract class Employe{
	protected 
		$id = 0, $nom = "", $prenom = "", $sexe = "", $salaire = "", 
		$heureArrivee, $heureDepart, $fonction = "", $service = "", $type;
	public function hydrate(array $donnees){
		$methodesEmployePeriode = EmployesManager::getAttributsEmploye("employeperiode");
		$methodesEmployePlein = EmployesManager::getAttributsEmploye("employeplein");
		foreach($donnees as $key => $value){
			$method = 'set'.ucfirst($key);
			if(method_exists($this, $method)){
				$this->$method($value);
			}else{
				if($this->type == "employeperiode"){
					if(!in_array($key, $methodesEmployePlein))
						trigger_error("La methode $methode n'a pas été trouvée", E_USER_WARNING);
				}elseif($this->type == "employeplein"){
					if(!in_array($key, $methodesEmployeperiode))
						trigger_error("La methode $methode n'a pas été trouvée", E_USER_WARNING);
				}
			}
		}
	}
	// Getters
	public function getId(){ return $this->id;}
	public function getNom(){ return $this->nom;}
	public function getPrenom(){ return $this->prenom;}
	public function getSexe(){ return $this->sexe;}
	public function getFonction(){return $this->fonction;}
	public function getService(){return $this->service;}
	public function getServiceName(){return Administration::serviceName($this->getService());}
	public function getSalaire(){ return $this->salaire;}
	public function getType(){return $this->type;}
	public function getHeureArrivee(){return $this->heureArrivee->getHeureComplet();}
	public function getHeureDepart(){return $this->heureDepart->getHeureComplet();}
	/**
		SETTERS
	*/
	public function setId($id){
		if(is_int($id)){
			$this->id = $id;
		}else{
			trigger_error("L'id doit etre un entier strictement positif", E_USER_WARNING);
		}
	}
	public function setNom($nom){
		if(is_string($nom)){
			$this->nom = $nom;
		}else{
			trigger_error("setNom : L'élément passé en parametre n'est de type string", E_USER_WARNING);
		}
	}
	public function setPrenom($prenom){
		if(is_string($prenom)){
			$this->prenom = $prenom;
		}else{
			trigger_error("setPrenom : L'élément passé en parametre n'est de type string", E_USER_WARNING);
		}
	}
	public function setSexe($sexe){
		if(is_string($sexe)){
			$this->sexe = $sexe;
		}else{
			trigger_error("setSexe : L'élément passé en parametre n'est de type string", E_USER_WARNING);
		}
	}
	public function setFonction($fonction){
		if(is_string($fonction))
			$this->fonction = $fonction;
		else
			trigger_error("La fonction doit etre une chaine de caracteres", E_USER_WARNING);
	}
	public function setService($service){
		$service = (int) $service;
		if($service > 0)
			$this->service = $service;
		else
			trigger_error("Le service doit etre un entier superieur à 0", E_USER_WARNING);
	}
	public function setSalaire($salaire){
		$salaire = (int) $salaire;
		if($salaire > 0){
			$this->salaire = $salaire;
		}else{
			trigger_error("Le salaire doit etre un entier strictement positif", E_USER_WARNING);
		}
	}
	public function setHeureArrivee(HeureSys $heureArrivee){
		$this->heureArrivee = $heureArrivee;
	}
	public function setHeureDepart(HeureSys $heureDepart){
		$this->heureDepart = $heureDepart;
	}
	/*
		Autres fonctions
	*/
	public function getNomComplet(){ return $this->nom." ".$this->prenom;}	

}
?>