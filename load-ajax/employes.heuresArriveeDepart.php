<?php
	session_start();
	function autoload($classe){
		require("../classes/".$classe.".class.php");
	}
	spl_autoload_register('autoload');
	$employesManager = new EmployesManager;
	$heuresManager = new HeuresManager;
	function showByMoment($id,$periode, $moment){
		global $employesManager, $heuresManager;
		$d = "d'";
		if($moment == "départ")
			$d = "de ";
		echo "<span>";
		echo "<label>".ucfirst($moment)." : </label>";
		echo "<font>";
			if($heuresManager->lineExist((int) $id, $periode, $moment)){
				$select = $heuresManager->getLine((int) $id, $periode, $moment);
				echo $select['heure'];
			}else{
				echo "L'heure $d".$moment." ce jour n'a pas été enregistrée";
			}
		echo "</font>";
		echo "</span>";
	}
	function showHeures($id,$periode){
		showByMoment($id,$periode, "arrivée");	
		showByMoment($id,$periode, "départ");	
	}
	if(isset($_GET['employes']) AND isset($_GET['mois']) AND isset($_GET['annee'])){
		$jour = $_GET['jour']; $mois = (int) $_GET['mois']; $annee = (int) $_GET['annee'];
		$periode = "";
		$mois_lettres = array(
			"Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
			"Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"
		);
		if($jour != "tous"){
			$date = new DateSys((int) $jour, (int) $mois, (int) $annee);
			$periode = $date->getdate();
		}else{
			$periode = $mois_lettres[((int) $mois)-1]." ".$annee;
		}
		// Modification du cocument parent
		?>
		<script language="javascript">
			var periode = "<?php echo $periode; ?>";
			$('#choix-periode h4').text("Arrivées et départs : "+periode);
		</script>
		<?php
		$idEmployes = explode('-', $_GET['employes']);
		for($i = 0;$i<count($idEmployes);$i++){
			echo "<div>";
			$id = $idEmployes[$i];
			$employe = $employesManager->getEmploye((int) $id);
			echo "<h5>".$employe->getNomComplet()."</h5>";
				echo "<div>";
			if($jour != "tous"){
				showHeures($id, $periode);
			}else{
				$date_debut = new DateSys(1, $mois, $annee);
				$nbJoursMois = $date_debut->getNbJoursMois();
				$date_fin = new DateSys($nbJoursMois, $mois, $annee);
				$calendar = new Calendar($date_debut, $date_fin);
				$dates = $calendar->getDatesNotWeekends();
				for($j = 0;$j<count($dates);$j++){
					$periode = $dates[$j]->getDate();
					echo "<h6>$periode</h6>";
					showHeures($id, $periode);
				}
			}
			echo "</div>";
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