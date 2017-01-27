<?php

namespace Paulp\JackontourBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Paulp\JackontourBundle\Entity\Tappe;

class GoogleapiController extends Controller
{
	/**
	 * @Route("/gapicode", name="gapicode")
	 * @Template()
	 * @Method("POST")
	 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
	 */
	public function gapicodeAction(Request $request)
	{
		
		if (!$request->isXmlHttpRequest()) {
			throw $this->createHttpNotFoundException();
		}
		
		$latlng = $request->get('lat').",".$request->get('lng');
		
		$googledata = $this->get('paulp_jackontour_googleapi')->getGoogleAddress($latlng);		
		return $googledata->getJson();
		
	}
	
	/**
	 * @Route("/gapiloc", name="gapiloc")
	 * @Template()
	 * @Method("GET")
	 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
	 */
	public function gapilocAction(Request $request)
	{
	
		if (!$request->isXmlHttpRequest()) {
			throw $this->createHttpNotFoundException();
		}
	
		$googledata = $this->get('paulp_jackontour_googleapi')->callGooglelocation();		
		return $googledata->getJson();
	
	}
	
}
