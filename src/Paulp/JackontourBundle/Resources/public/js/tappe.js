//COSTANTI
var openErr = '<div class="alert alert-danger alert-dismissible"><span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span> ';
var openInfo = '<div class="alert alert-info alert-dismissible"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span> ';
var dismissBtn = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
var closeDiv = '</div>';

function removeFormLocation(){
	deleteMarkers(cmarkers);
	cmarkers=[];
	$('#paulp_jackontourbundle_tappe_latlng').val('');
	$('#paulp_jackontourbundle_tappe_addr').val('').attr('readonly',false);
	$('#remloc').addClass('hidden');
	$('#geoloc').removeClass('hidden');
	$('#geolocmsg').html('');
	addClickListener();
}

function setFormFields(position){				    
	$('#remloc').removeClass('hidden');
	$('#geoloc').addClass('hidden');
	try{
		getGoogleAddress(position); //imposta paulp_jackontourbundle_tappe_addr
	} catch(err) {
		$('#geolocmsg').html(openErr+dismissBtn+err+closeDiv);
	}
}

function callbackGoogleAddressErr(){
	removeFormLocation();
	$('#geolocmsg').html(openErr+dismissBtn+'Posizione non riconosciuta. Controlla l\'indirizzo o seleziona un punto sulla mappa.'+closeDiv);
}
function callbackGoogleAddress(response){
	var address = response.geolocAddress;
	$('#paulp_jackontourbundle_tappe_latlng').val(response.geolocPosition);
	$('#paulp_jackontourbundle_tappe_addr').val(address).attr('readonly',true);
	$('#geolocmsg').html(openInfo+dismissBtn+'Impostato l&apos;indirizzo "'+address+'". Ti piace?'+closeDiv);
}

function getGeolocation(){
	try{
		getGoogleLocation();
	} catch(err) {
		callbackGoogleLocationErr();
	}
}

function callbackGoogleLocationErr(){
	$('#geolocmsg').html(openErr+dismissBtn+'Posizione non disponibile. Inserisci l\'indirizzo o seleziona un punto sulla mappa'+closeDiv);
}
function callbackGoogleLocation(response){
	var latlng = new google.maps.LatLng(response.geolocLat, response.geolocLng);
	setLocation(latlng);
	map.panTo(latlng);
}
 
//SET LOCATION UTENTE
function setLocation(position){
	if('disabled' === $('#paulp_jackontourbundle_tappe_addr').attr('disabled')){
		return;
	}
	
	deleteMarkers(cmarkers);
	cmarkers=[];
	var tempMarker = addMarker(position);
	tempMarker.setIcon(pinicon);
	cmarkers.push(tempMarker);
	setFormFields(position);
  
	google.maps.event.addListener(tempMarker, 'click', function(event) {
		removeFormLocation();  	
	});
}