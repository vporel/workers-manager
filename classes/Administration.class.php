<?php
	class Administration{
		public static function getServices(){
			$bd = BDFactory::getMysqlConnectionWithPDO();
			$select = $bd->query("SELECT * FROM services ORDER BY nom");
			$services = array();
			while($service = $select->fetch()){
				$services["".$service['id'].""] = $service['nom'];
			}
			return $services;
		}
		public static function serviceName($serviceID){
			$bd = BDFactory::getMysqlConnectionWithPDO();
			$select = $bd->query("SELECT * FROM services WHERE id = $serviceID");
			$service = $select->fetch();
			return $service['nom'];
		}
	}
?>