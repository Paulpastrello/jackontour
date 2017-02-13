//COSTANTI
var infowindow = new google.maps.InfoWindow();
var mcOptions = {maxZoom: 40, gridSize: 20, ignoreHidden: true, styles: clusterStyles};

//VARS
var map;
var markers = []; 	//quelli della lista	
var mmarkers = [];	//lista selezionata
var markerCluster;
var mmarkerCluster;
var cmarkers = [];	//selezionato dall'utente

// GoogleApi
function getGoogleAddress(position){
	try{
		if(position == null || position == '') throw 'Selezionare una posizione';
		$.ajax({
	        type: "POST",
	        url: 'gapicode',
	        data: {
	        	lat: position.lat(),
	        	lng: position.lng()
	        },
	        dataType: "json",
	        success: function(response) {
	        	callbackGoogleAddress(response);
	        },
	        error: function() {
	        	callbackGoogleAddressErr();
	        }
	    });
	} catch(err){
		throw err;
	}
}

function getGoogleLocation(){
	try {
		$.ajax({
	        type: "GET",
	        url: 'gapiloc',
	        success: function(response) {
	        	callbackGoogleLocation(response.geolocLat, response.geolocLng);	        	
	        },
	        error: function() {
	        	callbackGoogleLocationErr(null);
	        }
	    });
	} catch(err){
		throw err;
	}
}

//Geolocalizzazione html5
function getHTML5GeoLocation() {
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(showPosition, callbackGoogleLocationErr);
	} else {
		callbackGoogleLocationErr('NOT_SUPPORTED');
	}

	function showPosition(position) {
		//centralizzo in callbackGoogleLocation
		callbackGoogleLocation(position.coords.latitude, position.coords.longitude);
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
	var bounds = new google.maps.LatLngBounds();
	if(typeof(obj) === "undefined" || obj==null || obj.length<=0)
		obj = markers; //vuoto quindi leggo dalla lista markers
	
	if(obj.length>0){
		for(i=0;i<obj.length;i++) {
			bounds.extend(obj[i].getPosition());
		}		
		map.fitBounds(bounds);
	}
	
	//zoom automatico
	if(map.getZoom()>16)
		map.setZoom(16)
}

function rendering(clickable) {	
	clickable = typeof(clickable) === 'boolean' ? clickable : true;
	
 	var mapOptions = {
	  center: centerMap(list),
	  mapTypeId: google.maps.MapTypeId.TERRAIN,
	  maxZoom: 18 //zoom manuale
	}
	map = new google.maps.Map(document.getElementById('map-canvas'),mapOptions);
 			
 	initMarkers(list);
 	if(clickable){
 	 	addClickListener(); 		
 	}
  
	markerCluster = new MarkerClusterer(map, markers, mcOptions);
	mmarkerCluster = new MarkerClusterer(map, mmarkers, mcOptions);
	
	zoomMap();
}

function addClickListener(){
	google.maps.event.addListenerOnce(map, 'click', function(event) {
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

google.maps.event.addDomListenerOnce(window, 'load', rendering);
