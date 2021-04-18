<div id="links-header"> 
	<span id="derouler"><img src="images/traits-blanc.png"/></span>
	<span class="parent">
		<a href="index.php">Home</a>
	</span>
	<span class="parent">
		<a href="employes.php">&#x27A4; Employés</a>
		<span class="enfant"><hr width="80%"/>
			<a href="employes.php?page=ajouter">Ajouter un employé</a><hr width="80%"/>
			<a href="employes.php?page=liste">Liste des employés</a><hr width="80%"/>
		</span>
	</span>
	<span class="parent">
		<a href="utilisateurs.php">&#x27A4; Utilisateurs</a>
		<span class="enfant"><hr width="80%"/>
			<a href="utilisateurs.php?page=ajouter">Ajouter un utilisateur</a><hr width="80%"/>
			<a href="utilisateurs.php?page=liste">Liste des utilisateurs</a><hr width="80%"/>
		</span>
	</span>
	<span class="parent">
		<a class ="open-apropos">A propos</a>
	</span>
</div><div id="text">
	<a href="index.php"style="text-decoration:none;">
		<h2>Gestion employés - Afrique Electric</h2>
	</a>
</div><div id="last">
	<div class="barre">
	</div>
	<div id="user">
		<span id="img"><?php echo strtoupper(substr($_SESSION['user'], 0,1)); ?></span>
		<span id="nom">
			Bienvenu <strong><?php echo $_SESSION['user']; ?></strong><br>
			<a href="utilisateurs.php">Gérer les utilisateurs</a>
		</span>
	</div><div class="barre">
	</div><div id="quit">
		<a href="process/connexion.php?action=deconnecter"><img src="images/power-blanc.png"/></a>
	</div>
</div>
<div id="cover-charge">
	<div id="tourne1" class="tourne"></div>
	<div id="tourne2" class="tourne"></div>
</div>
<script language="javascript">
$(document).ready(function(){
	$('.open-apropos').click(function(){
		$('#apropos').slideDown(500);
	});
	imageToggle($('header #last #quit img'), 'power-blanc.png', 'power-red.png');
	var liensDeroules = false;
	$('header #links-header #derouler img').click(function(){
		if(!liensDeroules){
			$('header #links-header').animate({
				'width':'20%', 'height':'400px'
			}, 300, 'linear');
			$('header #links-header .parent').fadeIn(300, function(){$(this).css('display', 'block');});
			liensDeroules = true;
		}else{
			$('header #links-header').animate({
				'width':'10%', 'height':'100%'
			}, 300, 'linear');
			$('header #links-header .parent').fadeOut(300, function(){$(this).css('display', 'none');});
			liensDeroules = false;
		}
	});
});
</script>

