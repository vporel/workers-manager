<?php
	$bd = BDFactory::getMysqlConnectionWithPDO();
	$users = $bd->query("SELECT * FROM users");
	if($users->rowCount() > 0){
?>
<h2 id="bienvenu">Bienvenu dans l'application de gestion des employés
	<br><b>- Afrique electric -</b></h2>
<div class="connexion">
	<h2><img src="images/user-blanc-plein.png"/>Identification</h2>
	<p>
		<?php
			if(!empty($_SESSION['erreurs']['connexion'])){
				echo $_SESSION['erreurs']['connexion'];
				$_SESSION['erreurs']['connexion'] = "";
			}
		?>
	</p>
	<form method="post" action="process/connexion.php?action=connecter">
		<span class="text">
			<label for="username" autocompletition="false">Nom d'utilisateur</label>
			<input type="text" name="username" id="username"/>
		</span>
		<span class="text">
			<label for="password">Mot de passe</label>
			<input type="password" name="password" id="password"/>
		</span>
		<span class="submit">
			<input type="submit"name="connecter"value="Se connnecter"/>
			<center>Mot de passe oublié ? Contactez le développeur</center>
		</span>
	</form>
</div>
<?php
	}else{
?>
<div class="connexion enregistrer">
	<h2><img src="images/connexion.png"/>Enregistrer Utilisateur</h2>
	<p>
		<?php
			if(!empty($_SESSION['erreurs']['connexion'])){
				echo $_SESSION['erreurs']['connexion'];
				$_SESSION['erreurs']['connexion'] = "";
			}else
				echo "Il n'y a aucun utilisateur pour le moment";
		?>
	</p>
	<form method="post" action="process/connexion.php?action=enregistrer">
		<span class="text">
			<label for="username">Nom d'utilisateur</label>
			<input type="text" name="username" />
		</span>
		<span class="text">
			<label for="password">Mot de passe</label>
			<input type="password" name="password" id="password"/>
		</span>
		<span class="submit">
			<input type="submit"name="enregistrer"value="Enregistrer"/>
		</span>
	</form>
</div>
<?php
	}
?>
<script language="javascript">
	// Annulation de l'envoie du formulaire par la touche entrée
	$('.connexion form *').keydown(function(e){
		if(e.keyCode == 13){
			e.preventDefault();
		}
	});
	// Changement du label en fonction de laction
	$('.connexion .text label').click(function(){
		$(this).css({'font-size':'15px', 'color':'black'});
		$(this).parent().find('input').css('height','25px');
	});
	$('.connexion .text input').focus(function(){
		$(this).parent().find('label').css({'font-size':'15px', 'color':'black'});
		$(this).css('height','25px');
	});
	$('.connexion .text input').blur(function(){
		if($(this).val() == ""){
			$(this).parent().find('label').css({'font-size':'20px', 'color':'gray'});
			$(this).css('height','0');
		}
	});
	//Envoie du formulaire
	$('.connexion .submit input').click(function(e){
		e.preventDefault();
		var username = $('.connexion [type="text"]').val(),
			password = $('.connexion [type="password"]').val();
		if(username == "" || username.length < 5){
			$('.connexion p').text("Entrez un nom d'utilisateur ayant au moins 5 caracteres");
		}else if(password == "" || password.length < 8){
			$('.connexion p').text("Entrez un mot de passe ayant au moins 8 caracteres");
		}else{
			$('.connexion form').submit();
		}

	});
</script>