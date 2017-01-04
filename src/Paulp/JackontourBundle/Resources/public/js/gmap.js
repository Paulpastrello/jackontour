//COSTANTI
var urlGeoJSON = 'https://maps.googleapis.com/maps/api/geocode/json?';
var openErr = '<div class="alert alert-danger alert-dismissible"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> ';
var openInfo = '<div class="alert alert-info alert-dismissible"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span> ';
var dismissBtn = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
var closeDiv = '</div>';
var infowindow = new google.maps.InfoWindow();
var mcOptions = {maxZoom: 40, gridSize: 20, ignoreHidden: true};

//VARS
var map;
var markers = []; 	//quelli della lista	
var mmarkers = [];	//lista selezionata
var markerCluster;
var mmarkerCluster;
var cmarkers = [];	//selezionato dall'utente
var clickListener;

function setFormLocation(position){				    
	$('#remloc').removeClass('hidden');
	$('#geoloc').addClass('hidden');
	getAddress(position); //paulp_jackontourbundle_tappe_addr è impostato in questa funz
	google.maps.event.removeListener(clickListener);
}
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
 
//SET LOCATION UTENTE
function setLocation(position){
	deleteMarkers(cmarkers);
	cmarkers=[];
	var tempMarker = addMarker(position);
	tempMarker.setIcon(greenicon);
	cmarkers.push(tempMarker);
	setFormLocation(position);
  
	google.maps.event.addListener(tempMarker, 'click', function(event) {
		removeFormLocation();  	
	});
}

// INDIRIZZO
function getAddress(latlng){    	
	try{
		if(latlng == null || latlng == '') throw 'Selezionare una posizione';
	  	$.getJSON( urlGeoJSON+'latlng='+latlng.lat()+","+latlng.lng()	, function( obj ) {
	  		if(typeof(obj.results[0]) === 'undefined' || obj.results[0] == null || obj.results[0] == '') 
	  			$('#geolocmsg').html(openErr+dismissBtn+'Oooops! Impossibile trovare un indirizzo valido alle coordinate '+latlng+'.'+closeDiv);
	  		else{
	  			var address = obj.results[0].formatted_address;
	  			console.log(latlng.lat()+","+latlng.lng());
	  			$('#paulp_jackontourbundle_tappe_latlng').val(latlng.lat()+","+latlng.lng());
	  			$('#paulp_jackontourbundle_tappe_addr').val(address).attr('readonly',true);
	  			$('#geolocmsg').html(openInfo+dismissBtn+'Impostato l&apos;indirizzo "'+address+'". Ti piace?'+closeDiv);
			}
		});
	} catch(err){
		$('#geolocmsg').html(openErr+dismissBtn+err+closeDiv);
	}
}

// Geolocalizzazione
function getGeoLocation() {
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(showPosition, showError);
	} else { 
		$('#geolocmsg').html(openErr+dismissBtn+'Oooops! Il tuo browser non supporta la Geolocalizzazione.'+closeDiv);
	}

	function showPosition(position) {
		//converto nella position di google
		var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude)
		setLocation(latlng);
		map.panTo(latlng);
	}
	
	function showError(error) {
		switch(error.code) {
		    case error.PERMISSION_DENIED:
		    		$('#geolocmsg').html(openErr+dismissBtn+'Oooops! Hai negato la richiesta di Geolocalizzazione.'+closeDiv);
		        break;
		    case error.POSITION_UNAVAILABLE:
		    		$('#geolocmsg').html(openErr+dismissBtn+'Oooops! La tua posizione non è disponibile.'+closeDiv);
		        break;
		    case error.TIMEOUT:
		    		$('#geolocmsg').html(openErr+dismissBtn+'Oooops! Il sistema non ha risposto in tempo.'+closeDiv);
		        break;
		    case error.UNKNOWN_ERROR:
		    		$('#geolocmsg').html(openErr+dismissBtn+'Oooops! Errore sconosciuto.'+closeDiv);
		        break;
		}
	}
}
		    
//GESTIONE MAPPA
function centerMap(obj){
	if(typeof(obj) !== "undefined" && obj!=null && obj.length>0){
		var loc = (obj.length > 0) ? obj[0] : obj;
		return new google.maps.LatLng(loc[loc.length-3][0],loc[loc.length-3][1]);
	} else {
		return new google.maps.LatLng(45.394818199999996,9.149663199999964);
	}
}
function clearMap() {
	showHideMarkers(markers, false);
	showHideMarkers(mmarkers, false);
}
function zoomMap(obj) {
	map.setZoom(10);
	var bounds = new google.maps.LatLngBounds();
	if(typeof(obj) === "undefined" || obj==null || obj.length<=0)
		obj = markers; //vuoto quindi leggo dalla lista markers
	
	if(obj.length>0){
		for(i=0;i<obj.length;i++) {
			bounds.extend(obj[i].getPosition());
		}
		map.fitBounds(bounds);
	}
}

function rendering() {
 	var mapOptions = {
	  center: centerMap(list),
	  mapTypeId: google.maps.MapTypeId.TERRAIN
	}
	map = new google.maps.Map(document.getElementById('map-canvas'),mapOptions);
 			
 	initMarkers(list);
	addClickListener(); 
  
	markerCluster = new MarkerClusterer(map, markers, mcOptions);
	mmarkerCluster = new MarkerClusterer(map, mmarkers, mcOptions);
	
	zoomMap();
}

function addClickListener(){	
	google.maps.event.removeListener(clickListener);
	clickListener = google.maps.event.addListener(map, 'click', function(event) {
		setLocation(event.latLng);
	});
}
//aggiunta marker
function addMarker(position) {
	return new google.maps.Marker({
	  position: position,
	  icon: paulpicon,
	  map: map
	});
}

function addAdvancedMarker(obj){
	var myLatLng = obj[obj.length-3].split(',');
	if(myLatLng.length > 1){
		var mark = addMarker(new google.maps.LatLng(myLatLng[0], myLatLng[1]));
		if(obj[obj.length-2]!=null)mark.setZIndex(+obj[obj.length-2]);
		if(obj[obj.length-1]!=null){
			var htm = obj[obj.length-1];			
		  	mark.setTitle(htm);
//		  	$(htm).first().text()
		  	google.maps.event.addListener(mark, 'click', function(event) {
		  		infowindow.setContent(htm);
		    	infowindow.open(map, this);
		  	});
		}
	}
  return mark;
}

//markers di lista
function setMMarker(mmarker) {
	var removeIndex = existMMarker(mmarker.getPosition());
	if(removeIndex >= 0){
		mmarker.setMap(null);
		mmarkers.splice(removeIndex,1);
	} else {
		mmarkers.push(mmarker);
		//map.panTo(mmarker.getPosition());
	}
	mmarkerCluster.addMarkers(mmarkers);
	zoomMap(mmarkers)	
}
function existMMarker(position) {
	var MMindex = -1;					
	for(var i = 0; i < mmarkers.length; i++){
		var eqLat = position.lat()===mmarkers[i].getPosition().lat();
		var eqLng = position.lng()===mmarkers[i].getPosition().lng();
		if(eqLat && eqLng){
			MMindex=i;
			break;
		}
	}
	return MMindex;
}

function showMMarker(pos) {					
	clearMap();
	setMMarker(addAdvancedMarker(list[pos]));
	if(mmarkers.length > 0) showHideMarkers(mmarkers, true);
	else showHideMarkers(markers, true);
}
		
//gestione array markers
function initMarkers(arr){
	markers = [];
	mmarkers = [];
	for (var i = 0; i < arr.length; i++) {					    
		markers.push(addAdvancedMarker(arr[i]));
	}
}	
function showHideMarkers(arr, show) {
	for (var i = 0; i < arr.length; i++) {
		arr[i].setVisible(show)
	}
	markerCluster.repaint();
	mmarkerCluster.repaint();
}					
function deleteMarkers(arr) {
	for (var i = 0; i < arr.length; i++) {
		arr[i].setMap(null);
	}
}	
google.maps.event.addDomListener(window, 'load', rendering);