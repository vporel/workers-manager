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
		<script language="javascript" src="javascript/jquery.js"></script>
		<script language="javascript" src="javascript/doc-functions.js"></script>
		<link rel="stylesheet"type="text/css"href="styles/utilisateurs.css"/>
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
				<?php
					$pages = array("ajouter", "liste");
					if(isset($_GET['page']) AND in_array($_GET['page'], $pages)){
						switch($_GET['page']){
							case "ajouter" : include "includes/utilisateurs.ajouter.php"; break;
							case "liste" : include "includes/utilisateurs.liste.php"; break;
						}
					}else{
				?>
				<span id="infos-page"><b>Utilisateurs</b></span>
				<div id="liens">
					<a href="utilisateurs.php?page=ajouter"><div class="at-left">
						<img src="images/plus-bleu.png"data-change="images/plus-blanc.png"/>
						<strong>Ajouter</strong>
					</div></a><a href="utilisateurs.php?page=liste"><div>
						<img src="images/liste-bleu.png"data-change="images/liste-blanc.png"/>
						<strong>Liste des utilisateurs</strong>
					</div></a>
				</div>
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
				<?php
					}
				?>
			</section>
		</section>
	<?php 
		} else {
			header("Location:index.php");
		}
	?>
	</body>
</html>