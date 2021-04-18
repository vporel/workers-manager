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
	if(isset($_POST['to-save'])){
		$message = "";
		for($i = 0;$i<count(explode('-', $_POST['to-save']));$i++){
			$idEmploye = $idEmployes[$i];
			$currentEmploye = $employesManager->getEmploye($idEmploye);
			$dateDebut = new DateSys((int) $_POST['jourdebut-for-'.$idEmploye],(int) $_POST['moisdebut-for-'.$idEmploye],(int) $_POST['anneedebut-for-'.$idEmploye]);
			$dateFin = new DateSys((int) $_POST['jourfin-for-'.$idEmploye],(int) $_POST['moisfin-for-'.$idEmploye],(int) $_POST['anneefin-for-'.$idEmploye]);
			$congesManager = new CongesManager;
			if(!$congesManager->lineExist((int) $idEmploye, $dateDebut, $dateFin)){
				if($congesManager->add((int) $idEmploye, $dateDebut, $dateFin)){
				}else{
					$message = $currentEmploye->getNomComplet()." : Erreur lors de l'enregistrement veuillez réessayer! - ";
				}
			}else{
				$message .= $currentEmploye->getNomComplet()." : Cette période de congés a déjà été enregistrée - ";
			}
		}
		if($message == ""){
			header("Location:employes.php?page=liste&message=Congés-enregistrés-avec-succes-pour-les-employés-".$_POST['to-save']);
		}
	}
	
?>

<span id="infos-page">
	Employés / <b>Enregistrer congés</b>
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
<div id="conges">
	<div id="cadre">
		<form method="post">
			<input type="hidden"name="to-save"id="to-save"value=""/>
			<?php
				for($i = 1;$i<=count($idEmployes);$i++){
					$e = $employes[$i-1];
					$id = $e->getId();
					echo "<div id='bloc-for-$id' class='bloc'>";
						echo "<input type='checkbox'value='$id'checked name='check-for-".$id."' data-id='".$id."'/>";
						echo "<h3>".$e->getNomComplet()."</h3><hr/>";
						// Date de début
							echo "<label>Date de début</label>";
							echo '<input type="number" name="jourdebut-for-'.$id.'" class="jour" data-id="'.$id.'" required/>';
							echo '<input type="number" name="moisdebut-for-'.$id.'" class="mois" data-id="'.$id.'" required/>';
							echo '<input type="number" name="anneedebut-for-'.$id.'" class="annee" data-id="'.$id.'" required/>';
						// Date de fin
							echo "<label>Date de fin</label>";
							echo '<input type="number" name="jourfin-for-'.$id.'" class="jour" data-id="'.$id.'" required/>';
							echo '<input type="number" name="moisfin-for-'.$id.'" class="mois" data-id="'.$id.'" required/>';
							echo '<input type="number" name="anneefin-for-'.$id.'" class="annee" data-id="'.$id.'" required/>';
					echo "</div>";
				}
			?>
		</form>
	</div><div id="indications">
		<p>
			Enregistrez ici les congés pris par les employés. <br>
			<b>Décochez les cases à ne pas enregistrer.</b>
		</p>
	</div>
</div>
<script language="javascript">
	$('.bloc input[type="checkbox"]').each(function(){
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
		$('#conges form input[type="checkbox"]').each(function(){
			var parent = $(this).parent();
			if($(this).prop('checked')){
				idCoches.push($(this).attr('data-id'));
			}
		});
		$('#to-save').val(idCoches.join('-'));
		//Variables de validation
		var fieldEmpty = false,
			dateIncorrect = false;
		var regNumber2 = /^[0-9]{1,2}$/,
			regNumber4 = /^[0-9]{4}$/;
		for(var i = 0;i<idCoches.length;i++){
			var id = idCoches[i];
			$('#conges #bloc-for-'+id+' .jour, #conges #bloc-for-'+id+' .mois, #conges #bloc-for-'+id+' .annee').each(function(){
				if($(this).val() == "" || $(this).val() == " ")
					fieldEmpty = true;
				if($(this).attr('class') == "jour" || $(this).attr('class') == "mois"){
					if(!regNumber2.test($(this).val()))
						dateIncorrect = true;
					if(($(this).attr('class') == "jour" && parseInt($(this).val())>31) || ($(this).attr('class') == "mois" && parseInt($(this).val())>12))
						dateIncorrect = true;
				}
				if($(this).attr('class') == "annee" && !regNumber4.test($(this).val()))
					dateIncorrect = true;
			});
		}
		if(fieldEmpty)
			$('#message').text("Veuillez remplir tous les champs");
		else if(dateIncorrect)
			$('#message').text("La date entrée est incorrecte (format : jj-mm-yyyy)");
		else{
			$('#conges form').submit();
			coverCharge();
		}
	});
</script>

<?php
}else{
	header("Location:employes.php?page=liste");
}
?>