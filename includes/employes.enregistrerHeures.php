<?php
if(isset($_GET['employes']) AND $_GET['employes'] != ""){
	$employesManager = new EmployesManager;
	$idEmployes = explode('-', $_GET['employes']);
	$employes = array();
	for($i=0;$i<count($idEmployes);$i++){
		if($employesManager->employeExist((int) $idEmployes[$i]))
			$employes[] = $employesManager->getEmploye((int) $idEmployes[$i]);
		else
			header("Location:employes.php?page=liste");
	}
	if(isset($_POST['jour'])){
		$message = "";
		for($i = 0;$i<count(explode('-', $_POST['to-save']));$i++){
			$idEmploye = $idEmployes[$i];
			$currentEmploye = $employesManager->getEmploye($idEmploye);
			$date = new DateSys((int) $_POST['jour'],(int) $_POST['mois'],(int) $_POST['annee']);
			$date = $date->getDate();
			$moment = $_POST['moment'];
			$heure = new HeureSys((int) $_POST['heure-for-'.$idEmploye],(int) $_POST['minutes-for-'.$idEmploye]);
			$heure = $heure->getHeureComplet();
			$heuresManager = new HeuresManager;
			$d = "de ";
			if($moment == "arrivée")
				$d = "d'";
			if(!$heuresManager->lineExist((int)$idEmploye, $date, $moment)){
				if($heuresManager->add((int)$idEmploye, $date, $moment, $heure)){
				}else{
					$message = $currentEmploye->getNomComplet()." : Erreur lors de l'enregistrement veuillez réessayer! - ";
				}
			}else{
				$message .= $currentEmploye->getNomComplet()." : L'heure $d".$moment." le $date a déjà été enregistrée - ";
			}
		}
		if($message == ""){
			header("Location:employes.php?page=liste&message=Heure-$moment-le-$date-enregistrée-avec-succes-pour-les-employés-".$_POST['to-save']);
		}
	}
	
?>

<span id="infos-page">
	Employés / <b>Enregistrer heures</b>
	<span id="message">
		<?php 
			if(isset($message)) 
				echo $message; 
			else
				if(isset($_GET['message']))
					echo str_replace('-', ' ', $_GET['message']);
		?>
	</span>
	<input type="button" class="button submit" id="enregistrer" value="Enregistrer"/>
</span>
<div id="heures">
	<div id="cadre">
		<form method="post">
			<input type="hidden"name="to-save"id="to-save"value=""/>
			<span class='elmt'>
				<label for="jour">Date : </label>
				<input type="number" name="jour" id="jour" value="<?php echo date('d'); ?>"/>
				<input type="number" name="mois" id="mois" value="<?php echo date('m'); ?>"/>
				<input type="number" name="annee" id="annee" value="<?php echo date('Y'); ?>"/>
			</span>
			<span class='elmt'>
				<label for="moment">Moment : </label>
				<select id="moment" name="moment" value="">
					<option value="">Choisissez le moment de la journée...</option>
					<option value="arrivée">Arrivée</option>
					<option value="départ">Départ</option>
				</select>
			</span>
			<div id="blocs">
				<?php
					for($i = 1;$i<=count($idEmployes);$i++){
						$e = $employes[$i-1];
						$id = $e->getId();
						echo "<div id='bloc-for-$id'>";
							echo "<input type='checkbox'value='$id'checked name='check-for-".$id."' data-id='".$id."'/>";
							echo "<h3>".$e->getNomComplet()."</h3><hr/>";
							echo "<span class='elmt'><label for='heure'>Heure : </label>";
							echo '<input type="number" name="heure-for-'.$id.'" class="heure" value="'.date('H').'"data-id="'.$id.'"/> H ';
							echo '<input type="number" name="minutes-for-'.$id.'" class="minutes" value="'.date('i').'"data-id="'.$id.'"/></span>';
						echo "</div>";
					}
				?>
			</div>
			<p class="text">
				La date et l'heure sont celles de votre ordinateur mais si elles ne sont pas vraies n'hésitez pas à les changer.
			</p>
		</form>
	</div><div id="indications">
		<p>
			Enregistrez ici les heures d'arriver ou de départ des employés. <br>
			Des heures manquées seront retranchées dans le salaire à la fin du mois.<br>
			<b>Décochez les cases à ne pas enregistrer.
		</p>
	</div>
</div>
<script language="javascript">
	$('#blocs input[type="checkbox"]').each(function(){
		$(this).click(function(){
			var parent = $(this).parent();
			if($(this).prop('checked')){
				parent.find('h3').css({'color':'rgb(0,150,255)'});
				parent.find('hr').css({'border-color':'rgb(0,150,255)'});
			}else{
				parent.find('h3').css({'color':'black'});
				parent.find('hr').css({'border-color':'black'});
			}
		});
	});
	$('#enregistrer').click(function(e){
		var idCoches = new Array();
		e.preventDefault();
		$('#message').text("");
		$('#blocs input[type="checkbox"]').each(function(){
			var parent = $(this).parent();
			if($(this).prop('checked')){
				idCoches.push($(this).attr('data-id'));
			}
		});
		$('#to-save').val(idCoches.join('-'));
		//Variables de validation
		var fieldEmpty = false,
			dateIncorrect = false,
			heureIncorrect = false;
		var regNumber2 = /^[0-9]{1,2}$/,
			regNumber4 = /^[0-9]{4}$/;
		$('#heures jour, #heures #mois, #heures annee, #heures #moment').each(function(){
			if($(this).val() == "" || $(this).val() == " ")
				fieldEmpty = true;
		});
		for(var i = 0;i<idCoches.length;i++){
			var id = idCoches[i];
			$('#heures #bloc-for-'+id+' .heure, #heures #bloc-for-'+id+' .minutes').each(function(){
				if($(this).val() == "" || $(this).val() == " ")
					fieldEmpty = true;
				if(!regNumber2.test($(this).val()) || ($(this).attr('class') == "heure" && parseInt($(this).val())>24) || ($(this).attr('class') == "minutes" && parseInt($(this).val())>59))
					heureIncorrect = true;
			});
			$('#heures #bloc-for-'+id+' .heure[data-id="'+id+'"], #heures #bloc-for-'+id+' .minutes[data-id="'+id+'"]').each(function(){
				
			});
		}
		$('#heures #jour, #heures #mois, #heures #annee').each(function(){
			if((($(this).attr('id') == "jour" || $(this).attr('id') == "mois") && (!regNumber2.test($(this).val()))) || 
				($(this).attr('id') == "jour" && parseInt($(this).val())>31) || 
				($(this).attr('id') == "mois" && parseInt($(this).val())>12) || 
				($(this).attr('id') == "annee" && !regNumber4.test($(this).val())))
				dateIncorrect = true;
		});
		if(fieldEmpty)
			$('#message').text("Veuillez remplir tous les champs");
		else if(dateIncorrect)
			$('#message').text("La date entrée est incorrecte (format : jj-mm-yyyy)");
		else if(heureIncorrect)
			$('#message').text("L'heure entrée est incorrecte (format : hh-mm)");
		else{
			$('#heures form').submit();
			coverCharge();
		}
	});
</script>

<?php
}else{
	header("Location:employes.php?page=liste");
}
?>