// Change la source d'une image au passage de la souris
function imageToggle(image, realSrc, toggleSrc){
	image.mouseover(function(){
		$(this).attr('src', 'images/'+toggleSrc);
	});
	image.mouseout(function(){
		$(this).attr('src', 'images/'+realSrc);
	});
}
// Affiche le bloc de chargement
function coverCharge(){
	$('header #cover-charge').fadeIn(100);
}
function removeCoverCharge(){
	$('header #cover-charge').fadeOut(100);
}