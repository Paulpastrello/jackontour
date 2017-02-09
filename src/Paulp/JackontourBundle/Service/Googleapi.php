<?php

namespace Paulp\JackontourBundle\Service;

use Symfony\Component\HttpFoundation\JsonResponse;
use Paulp\JackontourBundle\Entity\Geoloc;

class Googleapi
{
	private $googlekey;
	
	public function __construct( $googlekey )
	{
		$this->googlekey = $googlekey;
	}
    
    public function getGoogleCoords($addr){
    	if($addr!=null && $addr!==''){
    		$cityclean = str_replace (" ", "+", $addr);
    		$details_url = 'http://maps.googleapis.com/maps/api/geocode/json?address=' . $cityclean . '&sensor=false';
    		return $this->callGoogleGeocode($details_url);
    	} else
    		return null;	
    }
    
    public function getGoogleAddress($latlng){
    	if($latlng!=null && $latlng!==''){
    		$details_url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng=' . $latlng . '&sensor=false';
    		return $this->callGoogleGeocode($details_url);
    	} else
    		return null;
    	    	
    }
    
    private function callGoogleGeocode($details_url){
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $details_url);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	$google = json_decode(curl_exec($ch));
    	
    	if(count($google->results)>0){
    		$result = current($google->results);
    		if($result!=null){
    			$location = $result->geometry->location;
    			$formatted_address = $result->formatted_address;
    			$address_components = $result->address_components;
    			 
    			$city = "";
    			foreach ($address_components as $addrc) {
    				if(current($addrc->types)==='administrative_area_level_3')
    					$city = $addrc->long_name;
    				else if(current($addrc->types)==='locality'){
    					$city = $addrc->long_name;
    					break;
    				}
    			}
    		}
    		
    		$geoloc = new Geoloc();
    		$geoloc->setLat($location->lat);
    		$geoloc->setLng($location->lng);
    		$geoloc->setCity($city);
    		$geoloc->setAddress($formatted_address);
    		return $geoloc;
    	}
    	else return null;
    }
    
    public function callGooglelocation(){
    	$ch = curl_init('https://www.googleapis.com/geolocation/v1/geolocate?key='.$this->googlekey);
	    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode("{}"));
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array( 
	    'Content-Type: application/json', 
	    'Content-Length:0 ' ) 
	    ); 
	    
    	$google = json_decode(curl_exec($ch));
    
    	if(isset($google->location)){
    		$location = $google->location;
    		
    		$geoloc = new Geoloc();
    		$geoloc->setLat($location->lat);
    		$geoloc->setLng($location->lng);
    		return $geoloc;
    	}
    	else return null;
    }
    
    
}
