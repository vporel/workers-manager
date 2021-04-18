<?php 
	$usersManager = new UsersManager;
	$listeUtilisateurs = $usersManager->getList();
?>

<span id="infos-page">
	Utilisateurs / <b>Liste des utilisateurs</b>
	<input type="button"class="button submit"onclick="window.location='utilisateurs.php?page=ajouter'" value="Ajouter"/>
</span>
<div id="liste">
	<table cellspacing="0">
		<tr id="thead">
			<th class="modifier thead"></th>
			<th class="id thead">#Id</th>
			<th class="username thead">Nom d'utilisateur</th>
			<th class="password thead">Mot de passe</th>
		</tr>
		<?php
			for($i = 0;$i < count($listeUtilisateurs); $i++){
				$user = $listeUtilisateurs[$i];
				echo "<tr class='table-body'>";
					if($user->getUsername() == $_SESSION['user'])
						echo "<td class='modifier tbody'title='Modifier'><img src='images/modifier-bleu.png'data-user='".$user->getId()."'/></td>";
					else
						echo "<td class='tbody'></td>";
					if($i < 10)
						echo "<td class='id tbody'>0".$user->getId()."</td>";	
					else
						echo "<td class='id tbody'>".$user->getId()."</td>";	
					echo "<td class='username tbody'>".$user->getUsername()."</td>";	
					echo "<td class='password tbody'>******** (chiffré en md5)</td>";				
				echo "</tr>";
			}
		?>
		<tr id="actions">
			<td colspan="2"><center>&#10149;</center></td>
		</tr>
	</table>
	<span id="message">
		<?php 
			if(isset($message)) 
				echo $message; 
			else
				if(isset($_GET['message']))
					echo str_replace('-', ' ', $_GET['message']);
		?>
	</span>
</div>
<script language="javascript">
	// Modification images
	imageToggle($('#liste .modifier img'), 'modifier-bleu.png', 'modifier-blanc.png');
	imageToggle($('#choix-periode #close img'), 'plus-bleu.png', 'plus-blanc.png');
	// Actions sur la modification
	$('#liste .modifier img').click(function(){
		var idEmploye = $(this).attr('data-user');
		window.location = "utilisateurs.php?page=ajouter&action=modifier&id="+idEmploye;
	});
	//Actions
	var nbCases = 0;
	$('#liste input[type="checkbox"]').each(function(){
		$(this).click(function(){
			if($(this).prop("checked")){
				nbCases++;
				$('#liste .forEmployes').addClass('active');
			}else
				nbCases--;
				if(nbCases <=0){
					$('#liste .forEmployes').removeClass('active');
				}
		});
	});
	$('#liste #actions #supprimer').click(function(){
		if(nbCases > 0){
			var users = new Array();
			$('#liste input[type="checkbox"]:checked').each(function(){
				users.push($(this).val());
			});
			var confirm = window.confirm("Etes vous sûr de vouloir supprimer les users N° "+users.join(', '));
			if(confirm){
				window.location = "users.php?page=liste&action=supprimer&users="+users.join('-');
			}
		}else{
			$('#liste #message').text("Selectionnez au moins une ligne à supprimer");
		}
	});
	
</script>