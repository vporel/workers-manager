<?php
	$modification = false;
	$type = "employeplein";
	$employesManager = new EmployesManager();
	if(isset($_GET['action']) AND $_GET['action'] == "modifier"){
		if(isset($_GET['id'])){
			if($employesManager->employeExist((int) $_GET['id'])){
				$modification = true;
				$employeAModifier = $employesManager->getEmploye((int) $_GET['id']);
				$type = $employeAModifier->getType();
			}
		}
	}
	function val($name, $param = ""){
		global $modification, $employeAModifier;
		if($modification){
			global $type;
			$attributs = EmployesManager::getAttributsEmploye($type);
			if(in_array($name, $attributs)){
				$method = 'get'.ucfirst($name);
				if($param == ""){
					echo $employeAModifier->$method();
				}else{
					$attribut = $employeAModifier->$method();
					switch($param){
						case "jour": echo substr($attribut, 0, 2);break;
						case "mois": echo substr($attribut, 3, 2);break;
						case "annee": echo substr($attribut, 6);break;
						case "heure": echo substr($attribut, 0, 2);break;
						case "minute": echo substr($attribut, 5);break;
					}
				}
			}else
				echo "";
		}
	}
	if(isset($_POST['nom'])){
		if($modification)
			$attributs = EmployesManager::getAttributsEmploye($type);
		else
			$attributs = EmployesManager::getAttributsEmploye($_POST['type']);
		$donnees = array();
		for($i = 0;$i<count($attributs);$i++){
			if(isset($_POST[$attributs[$i]])){
				$donnees[$attributs[$i]] = $_POST[$attributs[$i]];
			}
		}
		$typeEmploye = "";
		if($modification){
			$donnees['id'] = (int) $_GET['id'];
			$typeEmploye = $type;
		}else{
			$typeEmploye = $_POST['type'];
		}
		$donnees["heureArrivee"] = new HeureSys((int) $_POST["heureArrivee"], (int) $_POST["minuteArrivee"]);
		$donnees["heureDepart"] = new HeureSys((int) $_POST["heureDepart"], (int) $_POST["minuteDepart"]);
		if($typeEmploye == "employeplein"){
			$donnees["dateNaissance"] = new DateSys((int) $_POST["jourNaissance"], (int) $_POST["moisNaissance"], (int) $_POST["anneeNaissance"]);
			$donnees["dateEmbauche"] = new DateSys((int) $_POST["jourEmbauche"], (int) $_POST["moisEmbauche"], (int) $_POST["anneeEmbauche"]);
			$employe = new EmployePlein($donnees);
		}elseif($typeEmploye == "employeperiode"){
			$donnees["dateDebut"] = new DateSys((int) $_POST["jourDebut"], (int) $_POST["moisDebut"], (int) $_POST["anneeDebut"]);
			$donnees["dateFin"] = new DateSys((int) $_POST["jourFin"], (int) $_POST["moisFin"], (int) $_POST["anneeFin"]);
			$employe = new EmployePeriode($donnees);
		}
		if(!$modification){
			if($employesManager->nameExist($employe->getNom(), $employe->getPrenom())){
				$message = "Un employé du même nom et du même prénom existe déjà";
			}else{
				if($employesManager->add($employe)){
					$message = "Employé ".$employe->getNomComplet()." ajouté avec succès";
				}else{
					$message = "Erreur lors de l'ajout ";
				}
			}
		}else{
			if($employesManager->employeExist($employe->getId())){
				if($employesManager->update($employe)){
					header("Location:employes.php?page=liste&message=Modification-effectuée-avec-succès");
				}else{
					$message = "Erreur lors de la modification ";
				}
			}else{
				$message = "Modification impossible car l'employé n'existe pas";
			}
		}
	}
?>

<span id="infos-page">
	Employés / 
	<b><?php
		if(!$modification)
			echo "Ajouter un employé";
		else
			echo "Modification de l'employé N° ".$employeAModifier->getId()." : ".$employeAModifier->getNom()." ".$employeAModifier->getPrenom();
	?></b>
	<span id="message"><?php if(isset($message)) echo $message; ?></span>
	<input type="button" class="button other"value="Aide"id="Aide"/>
	<input type="button"class="button other"onclick="window.location='employes.php?page=liste'" value="Liste"/>
	<?php 
		if(!$modification)
			echo '<input type="button" value="Ajouter" name="ajouter" id="send" class="button submit"/>';
		else
			echo '<input type="button" value="Enregistrer" name="enregistrer" id="send" class="button submit"/>';
	?>
</span>
<div id="ajouter">
	<form method="post">
		<fieldset>
			<legend>Infos Personnelles</legend>
			<?php if(!$modification){ ?>
				<span id="select_type" class="select">
					<label for="type">Type d'employé</label>
					<select value="employeplein" name="type" id="type">
						<option value="employeplein">à plein temps</option>
						<option value="employeperiode">pour une période</option>
					</select>
				</span>
			<?php } ?>
			<span class="text infos-employe open">
				<label for="nom">Nom</label>
				<input type="text"name="nom" id="nom" value="<?php val('nom'); ?>"/>
			</span>
			<span class="text infos-employe open">
				<label for="nom">Prenom</label>
				<input type="text"name="prenom" id="prenom" value="<?php val('prenom'); ?>"/>
			</span>
			<span class="date infos-employe for-plein">
				<label for="jourNaissance">Date de naissance</label>
				<input type="number"name="jourNaissance" id="jourNaissance" class="jour", value="<?php val('dateNaissance','jour'); ?>"/>
				<input type="number"name="moisNaissance" id="moisNaissance" class="mois" value="<?php val('dateNaissance','mois'); ?>"/>
				<input type="number"name="anneeNaissance" id="anneeNaissance" class="annee"value="<?php val('dateNaissance','annee'); ?>"/>
			</span>
			<span class="sexe infos-employe open">
				<label>Sexe</label>
				<?php if(!$modification OR ($modification AND $employeAModifier->getSexe() == "Masculin")){ ?>
					<input type="radio" checked name="sexe"value="Masculin"/> Masculin
					<input type="radio"name="sexe"value="Feminin"/> Feminin
				<?php }else{ ?>
					<input type="radio" name="sexe"value="Masculin"/> Masculin
					<input type="radio" checked name="sexe"value="Feminin"/> Feminin
				<?php } ?>
			</span>
		</fieldset>
		<fieldset>
			<legend>Infos entreprise</legend>
			<span class="date infos-entreprise for-plein">
				<label for="jourEmbauche">Date embauche</label>
				<input type="number"name="jourEmbauche" id="jourEmbauche" class="jour" value="<?php val('dateEmbauche','jour'); ?>"/>
				<input type="number"name="moisEmbauche" id="moisEmbauche" class="mois" value="<?php val('dateEmbauche','mois'); ?>"/>
				<input type="number"name="anneeEmbauche" id="anneeEmbauche"class="annee" value="<?php val('dateEmbauche','annee'); ?>"/>
			</span>
			<span class="date infos-entreprise for-periode">
				<label for="jourDebut">Date début</label>
				<input type="number"name="jourDebut" id="jourDebut" class="jour" value="<?php val('dateDebut','jour'); ?>"/>
				<input type="number"name="moisDebut" id="moisDebut" class="mois" value="<?php val('dateDebut','mois'); ?>"/>
				<input type="number"name="anneeDebut" id="anneeDebut"class="annee" value="<?php val('dateDebut','annee'); ?>"/>
			</span>
			<span class="date infos-entreprise for-periode">
				<label for="jourFin">Date fin</label>
				<input type="number"name="jourFin" id="jourFin" class="jour" value="<?php val('dateFin','jour'); ?>"/>
				<input type="number"name="moisFin" id="moisFin" class="mois" value="<?php val('dateFin','mois'); ?>"/>
				<input type="number"name="anneeFin" id="anneeFin"class="annee" value="<?php val('dateFin','annee'); ?>"/>
			</span>
			<span class="infos-entreprise open">
				<label for="salaire">Salaire</label>
				<input type="number"name="salaire" id="salaire" value="<?php val('salaire'); ?>"/> <strong>FCFA</strong>
			</span>
			<span class="text infos-entreprise open">
				<label for="service">Service</label>
				<select name="service">
					<option value="" <?php if(!$modification){echo "selected"; }?>>Choisir le service...</option>
					<?php
						$services = Administration::getServices();
						foreach($services as $key => $value){
							echo "<option value='$key' ";
							if($modification AND $key == $employeAModifier->getService()){
								echo "selected";
							}
							echo ">$value</option>";
						}
					?>
				</select>
			</span>
			<span class="text infos-entreprise open">
				<label for="fonction">Fonction</label>
				<input type="text"name="fonction" id="fonction" value="<?php val('fonction'); ?>"/>
			</span>
			<span class="heures infos-entreprise open">
				<label for="heureArrivee">Heure d'arrivée</label>
				<input type="number"name="heureArrivee" id="heureArrivee" class="heure" value="<?php val('heureArrivee','heure'); ?>"/> H 
				<input type="number"name="minuteArrivee" id="minuteArrivee" class="minute" value="<?php val('heureArrivee','minute'); ?>"/>
			</span>
			<span class="heures infos-entreprise open">
				<label for="heureDepart">Heure de départ</label>
				<input type="number"name="heureDepart" id="heureDepart" class="heure" value="<?php val('heureDepart','heure'); ?>"/> H 
				<input type="number"name="minuteDepart" id="minuteDepart" class="minute" value="<?php val('heureDepart','minute'); ?>"/>
			</span>
			<span class="infos-entreprise for-plein">
				<label for="joursConges">Congés permis</label>
				<input type="number"name="joursConges" id="joursConges" value="<?php val('joursConges'); ?>"/> <strong>jours</strong>
			</span>
		</fieldset>
	</form>
</div>
<script language="javascript">
	var type="plein";
	type = "<?php echo $type; ?>";
	function changeType(){
		if(type == "employeplein"){
			$('.for-plein').show(200).addClass('open');
			$('.for-periode').hide(200).removeClass('open');
		}else if(type == "employeperiode"){
			$('.for-periode').show(200).addClass('open');
			$('.for-plein').hide(200).removeClass('open');
		}
	}
	changeType();
	$('select#type').change(function(){
		type = $(this).val();
		changeType();
	});
	$('#send').click(function(e){
		e.preventDefault();
		$('#message').text("");
		$('#ajouter fieldset input, #ajouter fieldset select').each(function(){
			$(this).css('border-color', 'gray');
		});
		//Variables de validation
		var fieldEmpty = false,
			datesIncorrect = false,
			salaireAndCongesIsNumber = true;
		$('#ajouter .open input, #ajouter .open select').each(function(){
			if($(this).val() == "" || $(this).val() == " "){
				fieldEmpty = true;
				$(this).css('border-color', 'red');
			}
		});
		var regJourMois = /^[0-9]{1,2}$/,
			regAnnee = /^[0-9]{4}$/;
		$('#ajouter .open .jour, #ajouter .open .mois, #ajouter .open .annee, #ajouter .open .heure, #ajouter .open .minute').each(function(){
			if((($(this).attr('class') == "jour" || $(this).attr('class') == "mois" || $(this).attr('class') == "heure" || $(this).attr('class') == "minute") && (!regJourMois.test($(this).val()))) || 
				($(this).attr('class') == "jour" && parseInt($(this).val())>31) || 
				($(this).attr('class') == "mois" && parseInt($(this).val())>12) || 
				($(this).attr('class') == "annee" && !regAnnee.test($(this).val())))
				datesIncorrect = true;
			if(($(this).attr('class') == "heure" && parseInt($(this).val())>24) || 
				($(this).attr('class') == "heure" && parseInt($(this).val())>60))
				datesIncorrect = true;
		});
		var regNumber = /^[0-9]{2,}$/;
		$('#ajouter #salaire, #ajouter .open #joursConges').each(function(){
			if(!regNumber.test($(this).val()))
				salaireAndCongesIsNumber = false;
		});	
		if(fieldEmpty)
			$('#message').text("Veuillez remplir tous les champs");
		else if(datesIncorrect)
			$('#message').text("Les dates ou les heures entrées sont incorrectes (format : jj-mm-yyyy / hh-mm)");
		else if(!salaireAndCongesIsNumber)
			$('#message').text("Le salaire et les jours de congés doivent être des nombres");
		else{
			$('#ajouter form').submit();
			coverCharge();
		}
	});
</script>