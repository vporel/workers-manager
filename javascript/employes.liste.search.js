 // Function de recherche lorsqu'on écrit
 $('#search-text').keyup(function(e){
 	$('#results-search').html('');
 	var valeur = $(this).val(),
 		regex = new RegExp(valeur, 'i'),
 		nomsTrouves = new Array(),
 		idsAssocies = new Array();
 	if(valeur == ""){
 		$('#results-search').html('');
 	}else{
	 	for(var i = 0;i<employesPleinNoms.length;i++){
	 		if(regex.test(employesPleinNoms[i])){
	 			nomsTrouves.push(employesPleinNoms[i]);
	 			idsAssocies.push(employesPleinIds[i]);
	 		}
	 	}
	 	for(var i = 0;i<employesPeriodeNoms.length;i++){
	 		if(regex.test(employesPeriodeNoms[i])){
	 			nomsTrouves.push(employesPeriodeNoms[i]);
	 			idsAssocies.push(employesPeriodeIds[i]);
	 		}
	 	}
	 	if(nomsTrouves.length > 0){
	 		for(var i = 0;i<nomsTrouves.length;i++){
	 			$('#results-search').append('<span data-encre="employe'+idsAssocies[i]+'">'+nomsTrouves[i]+'</span>');
	 		}
	 	}
	}
	$('#results-search span').each(function(){
		$(this).click(function(){
			var encre = $(this).attr('data-encre');
			var oldColor = $('tr#'+encre+' td').css('background-color');
			$('tr#'+$(this).attr('data-encre')+' td').css('background-color', 'rgba(0,150,255,.5');
			window.location = "#"+$(this).attr('data-encre');
			setTimeout(function(){
				$('tr#'+encre+' td').css('background-color',oldColor);
			}, 2500)
		});
	});
 });