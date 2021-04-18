<?php
if(isset($_GET['employes']) AND $_GET['employes'] != "" AND isset($_GET['type'])){
	$type = $_GET['type'];
	if($type == "employesPlein"){ 
		if(isset($_GET['jourdebut']) AND isset($_GET['jourfin']) 
			AND isset($_GET['moisdebut']) AND isset($_GET['moisfin']) AND isset($_GET['annee'])){
			$dateDebut = new DateSys((int) $_GET['jourdebut'], (int)$_GET['moisdebut'], (int)$_GET['annee']);
			$dateFin = new DateSys((int) $_GET['jourfin'], (int)$_GET['moisfin'], (int)$_GET['annee']);
			$calendar = new Calendar($dateDebut, $dateFin);
		}else{
			header("Location:employes.php?page=liste");
		}
	}
	$employesManager = new EmployesManager;
	$heuresManager = new HeuresManager;
	$congesManager = new CongesManager;
	$idEmployes = explode('-', $_GET['employes']);
	$employes = array();
	for($i=0;$i<count($idEmployes);$i++){
		if($employesManager->employeExist((int) $idEmployes[$i]))
			$employes[] = $employesManager->getEmploye((int) $idEmployes[$i]);
		else
			header("Location:employes.php?page=liste");
	}
	// Affichage des heures
	function showByMoment($id,$periode, $moment){
		global $employesManager, $heuresManager;
		$return = 0;
		$d = "d'";
		if($moment == "départ")
			$d = "de ";
		echo "<span>";
		echo "<label>".ucfirst($moment)." : </label>";
		echo "<font>";
			if($heuresManager->lineExist((int) $id, $periode, $moment)){
				$select = $heuresManager->getLine((int) $id, $periode, $moment);
				echo $select['heure'];
				$return = new HeureSys($select['heure']);
			}else{
				echo "L'heure $d".$moment." ce jour n'a pas été enregistrée";
			}
		echo "</font>";
		echo "</span>";
		return $return;
	}
	function showHeures($employe, $periode){
		global $employesManager, $heuresManager;
		$arrivee = showByMoment($employe->getId(),$periode, "arrivée");	
		$heuresManquees = "";
		$heureArrivee = new HeureSys($employe->getHeureArrivee());
		$heureDepart = new HeureSys($employe->getHeureDepart());
		if(is_object($arrivee)){
			if($arrivee->getHeure() <= $heureArrivee->getHeure()){
				if($arrivee->getMinutes() > $heureArrivee->getMinutes()+30){
					$heuresManquees = new HeureSys(0, $arrivee->getMinutes()- $heureArrivee->getMinutes()+30); 
				}
			}else{
				$arrivee->moins($heureArrivee);
				$heuresManquees = $arrivee;
			}
		}elseif($arrivee == 0){
			$heureDepart->moins($heureArrivee);
			$heuresManquees = $heureDepart;
		}
		return $heuresManquees;
	}
?>

<span id="infos-page">
	Employés / 
	<b>Reçus 
		<?php if($type == "employesPlein") echo ": du ".$dateDebut->getDate('l')." au ".$dateFin->getDate('l'); ?></b>
	<span id="message">
		<?php 
			if(isset($message)) 
				echo $message; 
			else
				if(isset($_GET['message']))
					echo str_replace('-', ' ', $_GET['message']);
		?>
	</span>
	<input type="button" class="button other"value="Aide"id="Aide"/>
	<input type="button"class="button other"onclick="window.location='employes.php?page=liste'" value="Liste"/>
</span>
<div id="recus">
<?php
for ($i=0; $i < count($employes); $i++) { 
$employe = $employes[$i];
if($type == "employesPeriode")
	$calendar = new Calendar($employe->getDateDebut('obj'), $employe->getDateFin('obj'));
$total_heures_manquees = new HeureSys;
$jours_conges = 0;
echo "<fieldset class='bloc' id='bloc-".$employe->getId()."'>";
	echo "<legend>".$employe->getNomComplet()."</legend>";
	echo "<div class='details'>";
		echo "<h4>Détails</h4>";
			if($type == "employesPlein"){
				echo "<span class='for-conges'>";
					echo "<h5>Congés</h5>";
					$conges = $congesManager->getConges($employe->getId(), $dateDebut, $dateFin);
					if(count($conges) > 0){
						echo "<ul>";
						for($j = 0;$j<count($conges);$j++){
							$conge = $conges[$j];
							echo "<li>Du <b>".$conge['date_debut']."</b> au <b>".$conge['date_fin'];
							echo "</b> : <b>".$conge['nb_jours']." jours</b></li>";
							$jours_conges += (int) $conge['nb_jours'];
						}
					echo "</ul>";
					}else{
						echo "<p>Aucun congés pris ce mois</p>";
					}
				echo "</span>";
			}
			echo "<span class='for-heures'>";
				echo "<h5>Heures d'arrivée</h5>";
				echo "<div>";
				$dates = $calendar->getDatesNotWeekends();
				for($j = 0;$j<count($dates);$j++){
					echo "<span class='heure'>";
					$periode = $dates[$j]->getDate();
					echo "<h6>$periode</h6>";
					echo "<label class='compte-heure'><input type='checkbox' checked id='heure-".$periode."for-".$i."'/>Prendre en compte</label>";
					$heuresManquees = showHeures($employe, $periode);
					echo "<span class='heures-manquees'> Heures manquées : <b>";
						if(is_object($heuresManquees)){
							echo $heuresManquees->getHeureComplet();
							$total_heures_manquees->plus($heuresManquees);
						}
					echo "</b></span>";
					echo "</Span>";
				}
					echo "</div>";
			echo "</span>";
			echo "<span class='for-salaire'>";
				echo "<h5>Calcul salaire pour le travail</h5>";
				echo "<ul>";
					echo "<li>Salaire Normal : <b>".$employe->getSalaire()." FCFA</b></li>";
					echo "<li>Jours congés : <b>$jours_conges jours</b></li>";
					echo "<li>Total heures manquées : <b>".$total_heures_manquees->getHeureComplet()."</b></li>";
					echo "<li>Salaire Final : <b></b></li>";
				echo "</ul>";
			echo "</span>";
		echo "<button id='imprimer-recu' class='button'>Imprimer le reçu</button>";
		echo "<button id='imprimer-details' class='button'onclick='print(\"index.php\");'>Imprimer les détails</button>";
	echo "</div>";
echo "</fieldset>";
}
?>
</div>
<script language="javascript">
	
</script>

<?php
}else{
	header("Location:employes.php?page=liste");
}
?>