<?php

namespace Paulp\JackontourBundle\Entity;

use Symfony\Component\HttpFoundation\JsonResponse;

class Geoloc
{
	const SEPARATOR = ',';
	const LAT = 'geolocLat';
	const LNG = 'geolocLng';
	const POSITION = 'geolocPosition';
	const ADDR = 'geolocAddress';
	const CITY = 'geolocCity';
	
	private $lat;
	private $lng;
	private $city;
	private $address;
	
	public function setLat($lat)
	{
		$this->lat = $lat;
		return $this;
	}
	
	public function getLat()
	{
		return $this->lat;
	}
	
	public function setLng($lng)
	{
		$this->lng = $lng;
		return $this;
	}
	
	public function getLng()
	{
		return $this->lng;
	}
	
	public function setCity($city)
	{
		$this->city = $city;
		return $this;
	}
	
	public function getCity()
	{
		return $this->city;
	}
	
	public function setAddress($address)
	{
		$this->address = $address;
		return $this;
	}
	
	public function getAddress()
	{
		return $this->address;
	}
	
	public function getPosition()
	{
		return $this->lat.self::SEPARATOR.$this->lng;
	}
	
	public function getArray()
	{
		return array(
				self::LAT => $this->lat,
				self::LNG => $this->lng,
				self::POSITION => $this->getPosition(),
				self::ADDR => $this->address,
				self::CITY => $this->city
		);
	}
	
	public function getJson()
	{
		return new JsonResponse($this->getArray());
	}
	
}
