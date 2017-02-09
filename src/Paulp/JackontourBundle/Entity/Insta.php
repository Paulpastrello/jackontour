<?php

namespace Paulp\JackontourBundle\Entity;

use Symfony\Component\HttpFoundation\JsonResponse;

class Insta
{
	const ID = 'id';
	
	const USER = 'user';
	const USER_ID = 'id';
	const USER_NAME = 'username';
	const USER_PP = 'profile_picture';
	const USER_FN = 'full_name';
	
	const IMG = 'images';
	const IMG_THUMB = 'thumbnail';
	const IMG_LOWRES = 'low_resolution';
	const IMG_STRES = 'standard_resolution';
	
	const LOC = 'location';
	const LOC_ID = 'id';
	const LOC_NAME = 'name';
	const LOC_LAT = 'latitude';
	const LOC_LNG = 'longitude';
	
	const CAPTION = 'caption';
	const CAPTION_ID = 'id';
	const CAPTION_TXT = 'text';
	const CAPTION_CT = 'created_time';

	private $id;
	private $user;
	private $images;
	private $location;
	private $caption;
	
	public function __construct( $data )
	{
		if($data!=null){
			$this->id = $data->id;
			$this->user = $data->user;
			$this->images = $data->images;
			$this->location = $data->location;
			$this->caption = $data->caption;
		}
	}
	
	public function setId($id)
	{
		$this->id = $id;
		return $this;
	}
	
	public function getId()
	{
		return $this->id;
	}

	public function setUser($user)
	{
		$this->user = $user;
		return $this;
	}
	
	public function getUser()
	{
		return $this->user;
	}	
	

	public function setImages($images)
	{
		$this->images = $images;
		return $this;
	}
	
	public function getImages()
	{
		return $this->images;
	}	

	public function setLocation($location)
	{
		$this->location = $location;
		return $this;
	}
	
	public function getLocation()
	{
		return $this->location;
	}	

	public function setCaption($caption)
	{
		$this->caption = $caption;
		return $this;
	}
	
	public function getCaption()
	{
		return $this->caption;
	}
	
	public function instaDecode($data)
	{		
		if($data!=null){
			$this->id = $data[self::ID];
			$this->user = $data[self::USER];
			$this->images = $data[self::IMG];
			$this->location = $data[self::LOC];
			$this->caption = $data[self::CAPTION];
			return $this;
		} else 
			return null;
	}

	
}
