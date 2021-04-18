//Actions  
	$('#liste .selectAll').click(function(){
		var table = $(this).parent().parent().parent().parent();
		if($(this).prop('checked')){
			table.find('input.selectLigne').each(function(){
				if(!$(this).prop('checked'))
					$(this).trigger('click');
			});
		}else{
			table.find('input.selectLigne').each(function(){
				if($(this).prop('checked'))
					$(this).trigger('click');
			});
		}
	});
	var nbCases_Plein = 0, nbCases_Periode = 0;
	function employesSelectionnes(tableId){
		var employes = new Array();
		$('#liste #'+tableId+' input.selectLigne:checked').each(function(){
			employes.push($(this).val());
		});
		return employes;
	}
	$('#liste input.selectLigne').each(function(){
		$(this).click(function(){
			var table = $(this).parent().parent().parent().parent();
			if($(this).prop("checked")){
				if(table.attr('id') == "employesPlein")
					nbCases_Plein++;
				else if(table.attr('id') == "employesPeriode")
					nbCases_Periode++;
				table.find('.tdActions a').addClass('active');
			}else{
				if(table.attr('id') == "employesPlein"){
					nbCases_Plein--;
					if(nbCases_Plein <=0){
						table.find('.tdActions a').removeClass('active');
					}
				}else if(table.attr('id') == "employesPeriode"){
					nbCases_Periode--;

					if(nbCases_Periode <=0){
						table.find('.tdActions a').removeClass('active');
					}
				}
			}
		});
	});
	var nbCases = 0;
	$('#liste .supprimer').click(function(){
		var tableId = $(this).parent().parent().parent().parent().attr('id');
		switch(tableId){
			case "employesPlein":nbCases = nbCases_Plein;break;
			case "employesPeriode":nbCases = nbCases_Periode;break;
		}
		if(nbCases > 0){
			var employes = employesSelectionnes(tableId);
			var confirm = window.confirm("Etes vous sûr de vouloir supprimer les employes N° "+employes.join(', '));
			if(confirm){
				window.location = "employes.php?page=liste&action=supprimer&employes="+employes.join('-');
			}
		}else{
			$('#message').text("Selectionnez au moins une ligne à supprimer");
		}
	});
// Affichage du bloc de choix de la période des heures
	$('#liste #actions .heuresArriveeDepart').click(function(){
		var tableId = $(this).parent().parent().parent().parent().attr('id');
		switch(tableId){
			case "employesPlein":nbCases = nbCases_Plein;break;
			case "employesPeriode":nbCases = nbCases_Periode;break;
		}
		if(nbCases > 0){
			$('#choix-periode').attr('data-table', tableId).css('top','30%');
			$('#choix-periode').slideDown(500);
		}else{
			$('#message').text("Selectionnez au moins une ligne");
		}
	});
// Clic sur le bouton de fermeture de la boite 
	$('#choix-periode #close, #afficher-conges #close, #mois-recu #close').click(function(){
		var parent = $(this).parent();
		parent.slideUp(1000, function(){
			$(this).css('top','30%');
			parent.find('#affichage').html("").hide();
			parent.find('#inset').show();
			parent.find('#charge').hide();
			if(parent.attr('id') == "choix-periode")
				parent.find('h4').text('Choisissez le jour ou la période pour les heures à afficher');
			else if(parent.attr('id') == "afficher-conges")
				parent.find('h4').text('Entrez la période pour les congés à afficher');
		});
	});
	// Clic sur le bouton afficher de la boite choix-periode
	$('#choix-periode #afficher').click(function(){
		var tableId = $('#choix-periode').attr('data-table');
		$('#choix-periode font').text("");
		//Variables de validation
		var jour = $('#choix-periode #choix-jour').val(),
			mois = $('#choix-periode #choix-mois').val(),
			annee = $('#choix-periode #choix-annee').val();
		if(mois == "" || annee == ""){
			$('#choix-periode font').text("Veuillez sélectionner un mois et une année");
		}else{
			var employes = employesSelectionnes(tableId);
			var j = "";
			if(jour == "" || jour == "tous")
				j = "tous";
			else
				j = jour;
			$('#choix-periode').css('top','25%');
			$('#choix-periode #inset').slideUp(500);
			$('#choix-periode #charge').slideDown(500);
			$('#choix-periode #affichage').load("load-ajax/employes.heuresArriveeDepart.php?employes="+employes.join('-')+"&jour="+j+"&mois="+mois+"&annee="+annee, function(){
				$('#choix-periode #charge').slideUp(500,function(){
					$('#choix-periode #affichage').slideDown(500);
				});
			});
		}
	});

//Affichage des congés

	$('#liste #actions .conges').click(function(){
		var tableId = $(this).parent().parent().parent().parent().attr('id');
		switch(tableId){
			case "employesPlein":nbCases = nbCases_Plein;break;
			case "employesPeriode":nbCases = nbCases_Periode;break;
		}
		if(nbCases > 0){
			$('#afficher-conges').css('top','30%');
			$('#afficher-conges').slideDown(500);
		}else{
			$('#message').text("Selectionnez au moins une ligne");
		}
	});
	// Clic sur le bouton afficher de la boite choix-periode
	$('#afficher-conges #afficher').click(function(){
		$('#afficher-conges font').text("");
		//Variables de validation
		var argumentsGET = new Array(),
			elmtsDate = ['jour', 'mois', 'annee'];
		for(var i = 0;i<3;i++){
			argumentsGET.push(elmtsDate[i]+"debut="+$('#afficher-conges #choix-'+elmtsDate[i]+'-debut').val());
			argumentsGET.push(elmtsDate[i]+"fin="+$('#afficher-conges #choix-'+elmtsDate[i]+'-fin').val());
		}
		var regNumber2 = /^[0-9]{1,2}$/,
			regNumber4 = /^[0-9]{4}$/,
			fieldEmpty = false,
			dateIncorrect = false;
		$('#afficher-conges .jour, #afficher-conges .mois, #afficher-conges .annee').each(function(){
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
		if(fieldEmpty){
			$('#afficher-conges font').text("Veuillez remplir tous les champs");
		}else if(dateIncorrect){
			$('#afficher-conges font').text("Les dates entrées sont incorrectes (format : jj-mm-yyyy)");
		}else if(($('#afficher-conges #choix-annee-fin').val()) > (parseInt($('#afficher-conges #choix-annee-debut').val())+1)){
			$('#afficher-conges font').text("L'année de fin doit dépasser l'année de début d'un au maximun (Exemple : 2019-2020)");
		}else{
			var employes = employesSelectionnes("employesPlein");
			$('#afficher-conges').css('top','25%');
			$('#afficher-conges #inset').slideUp(500);
			$('#afficher-conges #charge').slideDown(500);
			$('#afficher-conges #affichage').load("load-ajax/employes.afficherConges.php?employes="+employes.join('-')+"&"+argumentsGET.join('&'), function(){
				$('#afficher-conges #charge').slideUp(500,function(){
					$('#afficher-conges #affichage').slideDown(500);
				});
			});
		}
	});
// Affichage du bloc de choix du mois de la recu
	$('#liste #actions .genererRecu').click(function(){
		var tableId = $(this).parent().parent().parent().parent().attr('id');
		switch(tableId){
			case "employesPlein":nbCases = nbCases_Plein;break;
			case "employesPeriode":nbCases = nbCases_Periode;break;
		}
		if(nbCases > 0){
			if(tableId == "employesPeriode"){
				coverCharge();
				var employes = employesSelectionnes(tableId);
				window.location = "employes.php?page=genererRecu&employes="+employes.join('-')+"&type=employesPeriode";
			}else{
				$('#mois-recu').attr('data-table', tableId).css('top','30%');
				$('#mois-recu').slideDown(500);
			}
		}else{
			$('#message').text("Selectionnez au moins une ligne");
		}
	});
// Clic sur le bouton afficher de la boite mois-recu
	$('#mois-recu #generer').click(function(){
		var tableId = $('#mois-recu').attr('data-table');
		$('#mois-recu font').text("");
		//Variables de validation
		var jourDebut = $('#mois-recu #choix-jour-debut').val(),
			jourFin = $('#mois-recu #choix-jour-fin').val(),
			moisDebut = $('#mois-recu #choix-mois-debut').val(),
			moisFin = $('#mois-recu #choix-mois-fin').val(),
			annee = $('#mois-recu #choix-annee').val();
		var regNumber2 = /^[0-9]{1,2}$/;
		if(jourDebut == "" || moisDebut == "" || jourFin == "" || moisFin == "" || annee == ""){
			$('#mois-recu font').text("Remplissez tous les champs");
		}else if(!regNumber2.test(jourDebut) || !regNumber2.test(jourFin) || !regNumber2.test(moisDebut) || !regNumber2.test(moisFin)){
			$('#mois-recu font').text("Les jours et mois entrés sont incorrects");
		}else if((moisDebut != 12 && moisFin !=1) && (moisFin > moisDebut+1)){
			$('#mois-recu font').text("Le mois de fin ne peut pas être superieur au mois de debut +1");
		}else{
			coverCharge();
			var employes = employesSelectionnes(tableId);
			window.location = "employes.php?page=genererRecu&employes="+employes.join('-')+"&type=employesPlein&jourdebut="+jourDebut+"&moisdebut="+moisDebut+"&jourfin="+jourFin+"&moisfin="+moisFin+"&annee="+annee;
		}
	});
//Direction vers la page d'enregistrement des heures
$('#liste .enregistrerHeures').click(function(){
	var tableId = $(this).parent().parent().parent().parent().attr('id');
	switch(tableId){
		case "employesPlein":nbCases = nbCases_Plein;break;
		case "employesPeriode":nbCases = nbCases_Periode;break;
	}
	if(nbCases > 0){
		var employes = employesSelectionnes(tableId);
		var confirm = window.confirm("Vous allez être dirigé vers la page d'enregistrement des heures");
		if(confirm){
			window.location = "employes.php?page=enregistrerHeures&employes="+employes.join('-');
		}
	}else{
		$('#message').text("Selectionnez au moins une ligne");
	}
});
//Direction vers la page de prise des congés
$('#liste .enregistrerConges').click(function(){
	var tableId = $(this).parent().parent().parent().parent().attr('id');
	switch(tableId){
		case "employesPlein":nbCases = nbCases_Plein;break;
		case "employesPeriode":nbCases = nbCases_Periode;break;
	}
	if(nbCases > 0){
		var employes = employesSelectionnes(tableId);
		var confirm = window.confirm("Vous allez être dirigé vers la page d'enregistrement des congés pris");
		if(confirm){
			window.location = "employes.php?page=enregistrerConges&employes="+employes.join('-');
		}
	}else{
		$('#message').text("Selectionnez au moins une ligne");
	}
});