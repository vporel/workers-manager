<?php
	session_start();
	function autoload($classe){
		require("classes/".$classe.".class.php");
	}
	spl_autoload_register('autoload');
?>
<!DOCTYPE HTML>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Gestion employes</title>
		<script language="javascript" src="javascript/doc-functions.js"></script>
		<script language="javascript" src="javascript/jquery.js"></script>
		<link rel="stylesheet"type="text/css"href="styles/accueil.css"/>
		<link rel="stylesheet"type="text/css"href="styles/connexion.css"/>
		<link rel="stylesheet"type="text/css"href="styles/general-style.css"/>
	</head>
	<body>
	<?php if(isset($_SESSION['status']) AND $_SESSION['status'] == "openned"){ ?>
		<section id="body">
			<header>
				<?php include "includes/header.php"; ?>
			</header>
			<div id="apropos">
				<?php include "includes/apropos.php"; ?>
			</div>
			<section id="content">
				<div id="liens">
					<a href="employes.php"><div class="at-left">
						<img src="images/employe-bleu.png"data-change="images/employe-blanc.png"/>
						<strong>Employés</strong>
					</div></a><a href="utilisateurs.php"><div>
						<img src="images/user-bleu-plein.png"data-change="images/user-blanc-plein.png"/>
						<strong>Utilisateurs</strong>
					</div></a><div class="at-left open-apropos">
						<img src="images/apropos-bleu.png"data-change="images/apropos-blanc.png"/>
						<strong>A propos</strong>
					</div><a href="process/connexion.php?action=deconnecter"><div class="quit-link">
						<img src="images/close-red.png"data-change="images/close-blanc.png"/>
						<strong>Quitter</strong>
					</div></a>
				</div>
			</section>
			<script language="javascript">
				$('#liens div').mouseover(function(){
					var oldSrc = $(this).find('img').attr('src'),
						newSrc = $(this).find('img').attr('data-change');
					$(this).find('img').attr('src', newSrc);
					$(this).find('img').attr('data-change', oldSrc);
				});
				$('#liens div').mouseout(function(){
					var oldSrc = $(this).find('img').attr('src'),
						newSrc = $(this).find('img').attr('data-change');
					$(this).find('img').attr('src', newSrc);
					$(this).find('img').attr('data-change', oldSrc);
				});
			</script>
		</section>
	<?php 
		} else {
			include "connexion.php";
		}
	?>
	</body>
</html>