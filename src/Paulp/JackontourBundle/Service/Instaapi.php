<?php

namespace Paulp\JackontourBundle\Service;

use Symfony\Component\HttpFoundation\JsonResponse;
use Paulp\JackontourBundle\Entity\Insta;

class Instaapi
{
	private $client_id;
	private $client_secret;
	
	public function __construct( $client_id, $client_secret )
	{
		$this->client_id = $client_id;
		$this->client_secret = $client_secret;
	}
    
	public function getAuthorizeUrl($redirect_uri){
		$url = 'https://api.instagram.com/oauth/authorize/';
		$url = $url.'?client_id='.$this->client_id;
		$url = $url.'&redirect_uri='.$redirect_uri;
		$url = $url.'&scope=public_content&response_type=code';
		return $url;
	}
	
    public function getAccess_token($redirect_uri, $oauthcode){
    	if($oauthcode!=null && $oauthcode!==''){
    		$data = array(
				"client_id" => $this->client_id,
				"client_secret" => $this->client_secret,
				"grant_type" => "authorization_code",
				"redirect_uri" => $redirect_uri,
				"code" => $oauthcode
			);	
			
			$ch = curl_init('https://api.instagram.com/oauth/access_token');
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			
			$insta = json_decode(curl_exec($ch));
			
			if(isset($insta->access_token)){
				return $insta->access_token;
			}			
    	}
    	
   		return null;
    }
    
    public function getRecentTag($tag, $access_token){
    	if($access_token==null) return null;
    	
    	$ch = curl_init('https://api.instagram.com/v1/tags/'.$tag.'/media/recent?access_token='.$access_token);
    	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    	
    	$pics = json_decode(curl_exec($ch));
    	
    	$instass = array();
    	if(count($pics->data > 0)){
    		foreach ($pics->data as $insta) {
    			array_push($instass, new Insta($insta));
    		}    		
    		return $instass;
    	} else 
    		return null;
    }
    
    public function getTagList($tag, $access_token){
    	if($access_token==null) return null;
    	
    	$ch = curl_init('https://api.instagram.com/v1/tags/search?q='.$tag.'&access_token='.$access_token);
    	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    	
    	$pics = json_decode(curl_exec($ch));
    	
    	$instatags = array();
    	if(count($pics->data > 0)){
    		foreach ($pics->data as $tgg) {
    			array_push($instatags, $tgg);
    		}    		
    		return $instatags;
    	} else 
    		return null;
    	
    }
    
}
