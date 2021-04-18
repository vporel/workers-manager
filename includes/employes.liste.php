<?php  
	$employesManager = new EmployesManager;
	/* 
		CODE DE SUPRESSION DES MEMBRES DANS L'URL
	*/
	if(isset($_GET['action'])){
		if($_GET['action'] == "supprimer"){
			if(isset($_GET['employes'])){
				$employesASuppprimer = explode('-', $_GET['employes']);
				for($i=0;$i<count($employesASuppprimer);$i++){
					if(!$employesManager->employeExist( (int) $employesASuppprimer[$i])){
						header("Location:employes.php?page=liste");
					}else{
						if($employesManager->delete($employesManager->getEmploye( (int) $employesASuppprimer[$i]))){
							$message = "Les employés sélectionnés ont été supprimés avec succès";
						}else{
							$message = "Erreur lors de la suppression des employés";
						}
					}
				}
			}
		}
	}
	$EmployesPlein = $employesManager->getList("employeplein");
	$EmployesPeriode = $employesManager->getList("employeperiode");
	$employesPleinNoms = $employesManager->getList("employeplein", "noms");
	$employesPleinIds = $employesManager->getList("employeplein", "ids");
	$employesPeriodeNoms = $employesManager->getList("employeperiode", "noms");
	$employesPeriodeIds = $employesManager->getList("employeperiode", "ids");
?>

<span id="infos-page">
	Employés / <b>Liste des employés (<?php echo (count($EmployesPlein)+count($EmployesPeriode)); ?> employés)</b>
	<span id="message">
		<?php 
			if(isset($message)) 
				echo $message; 
			else
				if(isset($_GET['message']))
					echo str_replace('-', ' ', $_GET['message']);
		?>
	</span>
	<input type="button"class="button submit"onclick="window.location='employes.php?page=ajouter'" value="Ajouter"/>
</span>
<div id="liste">
	<div id="search">
		<img src="images/search-bleu.png"/>
		<input type="text" placeholder="Rechercher un employé"id="search-text"/>
		<input type="button" value="&#x27A4;"id="search-button"/>
	</div>
	<div id="results-search"></div>
	<fieldset>
	<legend>Employés à plein temps - <b><?php echo count($EmployesPlein); ?> employés</b></legend>
	<table cellspacing="0" id="employesPlein">
		<tr id="thead">
			<th class="cocher thead"></th>
			<th class="modifier thead"></th>
			<th class="id thead">#Id</th>
			<th class="nom thead">Nom</th>
			<th class="prenom thead">Prenom</th>
			<th class="sexe thead">Sexe</th>
			<th class="dateNaissance thead">Naiss.</th>
			<th class="dateEmbauche thead">Date emb.</th>
			<th class="quotite thead">Quotité</th>
			<th class="salaire thead">Salaire</th>
			<th class="service thead">Service</th>
			<th class="fonction thead">Fonction</th>
			<th class="joursConges thead">Jrs de cong.</th>
			<th class="congesUtilises thead">cong. utilises</th>
			<th class="heureArrivee thead">H. Arrivée</th>
			<th class="heureDepart thead">H. Depart</th>
		</tr>
		<?php
			$attrs = array("nom", "prenom", "sexe", "dateNaissance", "dateEmbauche", "quotite", "salaire", "service", "fonction", "joursConges", "congesUtilises", "heureArrivee", "heureDepart");
			for($j = 0;$j < count($EmployesPlein); $j++){
				$employe = $EmployesPlein[$j];
				echo "<tr class='table-body' id='employe".$employe->getId()."'>";
					echo "<td class='cocher tbody'><input type='checkbox'value='".$employe->getId()."' class='selectLigne'/></td>";
					echo "<td class='modifier tbody'title='Modifier'><img src='images/modifier-bleu.png'data-employe='".$employe->getId()."'/></td>";
					if($j < 10)
						echo "<td class='id tbody'>0".$employe->getId()."</td>";	
					else
						echo "<td class='id tbody'>".$employe->getId()."</td>";	
					for($i = 0;$i<count($attrs);$i++){
						$method = 'get'.ucfirst($attrs[$i]);
						if($attrs[$i] == "service")
							$method .= "Name";
						echo "<td class='".$attrs[$i]." tbody'>".$employe->$method()."</td>";
					}
							
				echo "</tr>";
			}
		?>
		<tr id="actions">
			<td colspan=""><center><input type="checkbox"class="selectAll"/></center></td>
			<td colspan="2" style="font:90% sans-serif;">Actions</td>
			<td colspan="13"class="tdActions">
				<a class="supprimer">Suppr.</a>
				<a class="enregistrerHeures">Enrg heure arr./déprt.</a>
				<a class="heuresArriveeDepart">Aff. Heures arr.&déprt.</a>
				<a class="enregistrerConges">Enrg congés</a>
				<a class="conges">Afficher congés</a>
				<a class="genererRecu">Générer recu</a>
			</td>
		</tr>
	</table>
	</fieldset>
	<fieldset>
	<legend>Employés pour périodes - <b><?php echo count($EmployesPeriode); ?> employés</b></legend>
	<table cellspacing="0" id="employesPeriode">
		<tr id="thead">
			<th class="cocher thead"></th>
			<th class="modifier thead"></th>
			<th class="id thead">#Id</th>
			<th class="nom thead">Nom</th>
			<th class="prenom thead">Prenom</th>
			<th class="sexe thead">Sexe</th>
			<th class="salaire thead">Salaire</th>
			<th class="service thead">Service</th>
			<th class="fonction thead">Fonction</th>
			<th class="dateDebut thead">Date de début</th>
			<th class="dateFin thead">Date de fin</th>
			<th class="heureArrivee thead">H. Arrivée</th>
			<th class="heureDepart thead">H. Depart</th>
		</tr>
		<?php
			$attrs = array("nom", "prenom", "sexe", "salaire", "service", "fonction", "dateDebut", "dateFin", "heureArrivee", "heureDepart");
			for($j = 0;$j < count($EmployesPeriode); $j++){
				$employe = $EmployesPeriode[$j];
				echo "<tr class='table-body' id='employe".$employe->getId()."'>";
					echo "<td class='cocher tbody'><input type='checkbox'value='".$employe->getId()."' class='selectLigne'/></td>";
					echo "<td class='modifier tbody'title='Modifier'><img src='images/modifier-bleu.png'data-employe='".$employe->getId()."'/></td>";
					if($i < 10)
						echo "<td class='id tbody'>0".$employe->getId()."</td>";	
					else
						echo "<td class='id tbody'>".$employe->getId()."</td>";	
					for($i = 0;$i<count($attrs);$i++){
						$method = 'get'.ucfirst($attrs[$i]);
						if($attrs[$i] == "service")
							$method .= "Name";
						echo "<td class='".$attrs[$i]." tbody'>".$employe->$method()."</td>";
					}
							
				echo "</tr>";
			}
		?>
		<tr id="actions">
			<td><center><input type="checkbox"class="selectAll"/></center></td>
			<td colspan="2" style="font:90% sans-serif;">Actions</td>
			<td colspan="13"class="tdActions">
				<a class="supprimer">Suppr.</a>
				<a class="enregistrerHeures">Enrg heure arr./déprt.</a>
				<a class="heuresArriveeDepart">Aff. Heures arr.&déprt.</a>
				<a class="genererRecu">Générer recu</a>
			</td>
		</tr>
	</table>
	</fieldset>
	<fieldset id="abbr">
		<span>naiss : Naissance</span>
		<span>emb : Embauche</span>
		<span>Jrs : jours</span>
		<span>cong. : congés</span>
		<span>H : Heure</span>
		<span>Suppr : Supprimer</span>
		<span>Enrg : Enregistrer</span>
		<span>arr : Arrivée</span>
		<span>déprt : Départ</span>
		<span>Aff : Afficher</span>
		<span>emb : Embauche</span>
		<span>emb : Embauche</span>
	</fieldset>
	<div id="choix-periode" class="afficher-elmts" data-table="">
		<span id="close"><img src="images/plus-bleu.png"/></span>
		<h4>Choisissez le jour ou la période pour les heures à afficher</h4>
		<div id="inset">
		<!-- BLOC DU CHOIX DE LA PERIODE A AFFICHER -->
		<span>
			<label>Jour : </label>
			<select id="choix-jour"value="">
				<option value="">Choisissez le numéro du jour...</option>
				<option value="tous">Tous les jours</option>
				<?php for($i = 1;$i<=31;$i++){ echo "<option value='$i'>$i</option>"; } ?>
			</select>
		</span>
		<span>
			<label>Mois : </label>
			<select id="choix-mois"value="">
				<option value="">Choisissez le mois...</option>
				<?php for($i = 1;$i<=12;$i++){ echo "<option value='$i'>$i</option>"; } ?>
			</select>
		</span>
		<span>
			<label>Année : </label>
			<select id="choix-annee"value="">
				<option value="">Choisissez l'année...</option>
				<?php for($i = (int) date('Y');$i>=2018;$i--){ echo "<option value='$i'>$i</option>"; } ?>
			</select>
		</span>
		<font></font>
		<input type="button" id="afficher" value="Afficher"/>
		</div>
		<div id="affichage"></div>
		<div id="charge">
			<span id="tourne1"class="tourne"></span>
			<span id="tourne2"class="tourne"></span>
		</div>
	</div>
	<div id="afficher-conges" class="afficher-elmts">
		<span id="close"><img src="images/plus-bleu.png"/></span>
		<h4>Entrez la période pour les congés à afficher</h4>
		<div id="inset">
		<!-- BLOC DU CHOIX DE LA PERIODE A AFFICHER -->
		<span>
			<label>Date Debut : </label>
			<input type="number" id="choix-jour-debut" class="jour"/>
			<input type="number" id="choix-mois-debut" class="mois"/>
			<input type="number" id="choix-annee-debut" class="annee"/>
		</span>
		<span>
			<label>Date Fin : </label>
			<input type="number" id="choix-jour-fin" class="jour"/>
			<input type="number" id="choix-mois-fin" class="mois"/>
			<input type="number" id="choix-annee-fin" class="annee"/>
		</span>
		<font></font>
		<input type="button" id="afficher" value="Afficher"/>
		</div>
		<div id="affichage"></div>
		<div id="charge">
			<span id="tourne1"class="tourne"></span>
			<span id="tourne2"class="tourne"></span>
		</div>
	</div>
	<div id="mois-recu" class="afficher-elmts">
		<span id="close"><img src="images/plus-bleu.png"/></span>
		<h4>Choisissez le mois et l'année</h4>
		<div id="inset">
		<!-- BLOC DU CHOIX DE LA PERIODE A AFFICHER -->
		<span>
			<label>Du : </label>
			<input type="number" id="choix-jour-debut" class="jour"/>
			<input type="number" id="choix-mois-debut" class="mois"/>
		</span>
		<span>
			<label>Au : </label>
			<input type="number" id="choix-jour-fin" class="jour"/>
			<input type="number" id="choix-mois-fin" class="mois"/>
		</span>
		<span>
			<label>Année : </label>
			<select id="choix-annee"value="">
				<option value="">Choisissez l'année...</option>
				<?php for($i = (int) date('Y');$i>=2018;$i--){ echo "<option value='$i'>$i</option>"; } ?>
			</select>
		</span>
		<font></font>
		<input type="button" id="generer" value="Générer"/>
		</div>
	</div>
</div>
<script language="javascript">
	// Modification images
	imageToggle($('#liste .modifier img'), 'modifier-bleu.png', 'modifier-blanc.png');
	imageToggle($('.afficher-elmts #close img'), 'plus-bleu.png', 'plus-blanc.png');
	// Actions sur la modification
	$('#liste .modifier img').click(function(){
		var idEmploye = $(this).attr('data-employe');
		window.location = "employes.php?page=ajouter&action=modifier&id="+idEmploye;
	});
	// Variables pour la recherche
	var employesPleinNoms = "<?php echo implode($employesPleinNoms, '-'); ?>",
		employesPeriodeNoms = "<?php echo implode($employesPeriodeNoms, '-'); ?>",
		employesPleinIds = "<?php echo implode($employesPleinIds, '-'); ?>",
		employesPeriodeIds = "<?php echo implode($employesPeriodeIds, '-'); ?>";
	employesPleinNoms = employesPleinNoms.split('-');
	employesPleinIds = employesPleinIds.split('-');
	employesPeriodeNoms = employesPeriodeNoms.split('-');
	employesPeriodeIds = employesPeriodeIds.split('-');
</script>
<script language="javascript"src="javascript/employes.liste.actions.js"></script>
<script language="javascript"src="javascript/employes.liste.search.js"></script>