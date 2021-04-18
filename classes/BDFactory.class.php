<?php
class BDFactory{
	public static function getMysqlConnectionWithPDO(){
		$bd = new PDO("mysql:host=localhost;dbname=gestion_employes", "root", "");
		$bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $bd;
	}
}
?>