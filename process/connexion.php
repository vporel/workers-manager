<?php
	session_start();
	require "../classes/BDFactory.class.php";
	if(isset($_GET['action']) AND in_array($_GET['action'], array("connecter", "deconnecter", "enregistrer"))){
		$bd = BDFactory::getMysqlConnectionWithPDO();
		if($_GET['action'] == "connecter"){
			$username = $_POST['username'];
			$password = md5($_POST['password']);
			// Vérification de l'existence de l'utilisateur
			$select = $bd->query("SELECT * FROM users WHERE username = '$username'");
			if($select->rowCount() > 0){
				$user = $select->fetch();
				if($user['password'] == $password){
					$_SESSION['status'] = "openned";
					$_SESSION['user'] = $username;
				}else{
					$_SESSION['erreurs']['connexion'] = "Le mot de passe saisi est incorrect";
				}
			}else{
				$_SESSION['erreurs']['connexion'] = "Cet utilisateur n'existe pas";
			}
		}elseif($_GET['action'] == "enregistrer"){
			$insertion = $bd->prepare("INSERT INTO users(username, password) VALUES(?,?)");
			if($insertion->execute(array($_POST['username'], md5($_POST['password'])))){
				$_SESSION['erreurs']['connexion'] = "Enregistrement réussi! Connectez-vous à présent.";
			}else{
				$_SESSION['erreurs']['connexion'] = "L'enregistrement a échoué. Veuillez contacter le développeur";
			}
		}else{
			session_unset($_SESSION['status']);
			session_unset($_SESSION['user']);
			session_unset($_SESSION['erreurs']);
			session_destroy();
		}
		header("Location:../index.php");
	}else{
		?>
	<!DOCTYPE html>
	<html>
	<head>
		<title>Error</title>
		<script language="javascript"src="../javascript/jquery.js"></script>
		<link rel="stylesheet"type="text/css"href="../styles/error.css"/>
	</head>
	<body>
		<div id="bloc-erreur">
			<h1>Une erreur est survenue</h1>
			<img src="../images/error-red.png"/>
			<p>
				Les informations sur cette page ne sont pas suffisantes pour le traitement
				<a href="../index.php">Retourner à l'accueil &#10094;&#10094;</a>
			</p>
		</div>
	</body>
	</html>
		<?php
	}
?>