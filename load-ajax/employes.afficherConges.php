<?php
	session_start();
	function autoload($classe){
		require("../classes/".$classe.".class.php");
	}
	spl_autoload_register('autoload');
	$employesManager = new EmployesManager;
	$congesManager = new CongesManager;
	
	if(isset($_GET['employes']) AND !empty($_GET['employes'])){
		$jourdebut = $_GET['jourdebut']; $moisdebut = $_GET['moisdebut']; $anneedebut = $_GET['anneedebut'];
		$jourfin = $_GET['jourfin']; $moisfin = $_GET['moisfin']; $anneefin = $_GET['anneefin'];
		$dateDebut = new DateSys((int) $jourdebut, (int) $moisdebut, (int) $anneedebut);
		$dateFin = new DateSys((int) $jourfin, (int) $moisfin, (int) $anneefin);
		// Modification du cocument parent
		?>
		<script language="javascript">
			var dateDebut = "<?php echo $dateDebut->getDate(); ?>",
				dateFin = "<?php echo $dateFin->getDate(); ?>";
			$('#afficher-conges h4').text("Congés du "+dateDebut+" au "+dateFin);
		</script>
		<?php
		$idEmployes = explode('-', $_GET['employes']);
		for($i = 0;$i<count($idEmployes);$i++){
			echo "<div>";
			$id = $idEmployes[$i];
			$employe = $employesManager->getEmploye((int) $id);
			echo "<h5>".$employe->getNomComplet()."</h5>";
			$conges = $congesManager->getConges($id, $dateDebut, $dateFin);
			if(count($conges) > 0){
				echo "<ul>";
					for($j = 0;$j<count($conges);$j++){
						$conge = $conges[$j];
						echo "<li>Du <b>".$conge['date_debut']."</b> au <b>".$conge['date_fin'];
						echo "</b> : <b>".$conge['nb_jours']." jours</b></li>";
					}
				echo "</ul>";
			}else{
				echo "<h6>Aucun congé pris sur cette période.</h6>";
			}
			echo "</div>";
		}
	}else{
		?>
	<!DOCTYPE html>
	<html>
	<head>
		<title>Error</title>
		<script language="javascript"src="javascript/jquery.js"></script>
		<link rel="stylesheet"type="text/css"href="styles/error.css"/>
	</head>
	<body>
		<div id="bloc-erreur">
			<h1>Une erreur est survenue</h1>
			<img src="images/error-red.png"/>
			<p>
				Les informations sur cette page ne sont pas suffisantes pour le traitement
				<a href="index.php">Retourner à l'accueil &#10094;&#10094;</a>
			</p>
		</div>
	</body>
	</html>
		<?php
	}
?>