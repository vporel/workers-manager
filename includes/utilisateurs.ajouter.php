<?php
	$modification = false;
	$userManager = new UsersManager();
	if(isset($_GET['action']) AND $_GET['action'] == "modifier"){
		if(isset($_GET['id'])){
			if($userManager->userExist(new User(array("id"=>(int) $_GET['id'])))){
				$userAModifier = $userManager->getUser((int) $_GET['id']);
				if($userAModifier->getUsername() == $_SESSION['user']){
					$modification = true;
				}else{
					header("Location:utilisateurs.php?page=liste&message=Vous-avez-essayé-de-modifier-un-autre-utilisateur");
				}
			}
		}
	}
	if(isset($_POST['username'])){
		$attributs = UsersManager::getAttributsUser();
		$donnees = array();
		for($i = 0;$i<count($attributs);$i++){
			if(isset($_POST[$attributs[$i]])){
				$donnees[$attributs[$i]] = $_POST[$attributs[$i]];
			}
		}
		$donnees['password'] = md5($donnees['password']);
		if($modification)
			$donnees['id'] = (int) $_GET['id'];
		$user = new User($donnees);
		if(!$modification){
			if($userManager->usernameExist($user->getUsername())){
				$message = "Un utilisateur du même nom existe déjà";
			}else{
				if($userManager->add($user)){
					$message = "Utilisateur ".$user->getUsername()." ajouté avec succès";
				}else{
					$message = "Erreur lors de l'ajout ";
				}
			}
		}else{
			if($userManager->userExist($user)){
				if($userManager->update($user)){
					header("Location:utilisateurs.php?page=liste&message=Modification-effectuée-avec-succès");
				}else{
					$message = "Erreur lors de la modification ";
				}
			}else{
				$message = "Modification impossible car cet utilisateur n'existe pas";
			}
		}
	}
?>

<span id="infos-page">
	Utilisateurs / 
	<b><?php
		if(!$modification)
			echo "Ajouter un utilisateur";
		else
			echo "Modification de l'utilisateur N° ".$userAModifier->getId()." : ".$userAModifier->getUsername();
	?></b>
	<span id="message"><?php if(isset($message)) echo $message; ?></span>
	<input type="button" class="button other"value="Aide"id="Aide"/>
	<input type="reset" value="Reset"class="button other"/>
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
			<legend>Informations de connexion</legend>
			<span class="text">
				<label for="username">Nom d'utilisateur</label>
				<input type="text"name="username" id="username" 
					value="<?php if($modification){	echo $userAModifier->getUsername();} ?>"/>
			</span>
			<span class="text">
				<label for="password">Mot de passe</label>
				<input type="text"name="password" id="password" />
			</span>
		</fieldset>
	</form>
</div>
<script language="javascript">
	$('#send').click(function(e){
		e.preventDefault();
		$('#message').text("");
		//Variables de validation
		var fieldEmpty = false;
		$('#ajouter fieldset input').each(function(){
			if($(this).val() == "" || $(this).val() == " ")
				fieldEmpty = true;
		});
		if(fieldEmpty)
			$('#message').text("Veuillez remplir tous les champs");
		else if($('#ajouter #username').val().length < 5){
			$('#message').text("Le username d'utilisateur doit avoir au moins 6 caractères");
		}else if($('#ajouter #password').val().length < 8){
			$('#message').text("Le mot de passe doit avoir au moins 8 caractères");
		}
		else
			$('#ajouter form').submit();
	});
</script>