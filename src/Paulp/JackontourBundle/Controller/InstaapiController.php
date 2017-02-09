<?php

namespace Paulp\JackontourBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Paulp\JackontourBundle\Entity\Insta;

class InstaapiController extends Controller
{
	/**
	 * @Route("/instaaccess")
	 * @Template()
	 * @Method("GET")
	 */
	public function instaaccessAction(Request $request)
	{
		$session = $request->getSession();
		$tag = $request->get('tag');
		
		if($session->get("instatoken")!=null){}
		else {
			$access_token = $this->get('paulp_jackontour_instaapi')
				->getAccess_token($this->generateUrl('paulp_jackontour_instaapi_instaaccess', array('tag' => $tag), UrlGeneratorInterface::ABSOLUTE_URL), $request->get('code'));
			
			if($access_token!=null){
				$session->set('instatoken', $access_token);
			}	
		}

		return $this->redirectToRoute('instapics', array('tag' => $tag));
	}
	
	
	
	/**
	 * @Route("/instapics/{tag}", defaults={"tag" = "jackontour"}, name="instapics")
	 * @Template()
	 * @Method("GET")
	 */
	public function instapicsAction(Request $request, $tag = 'jackontour')
	{
		$session = $request->getSession();
		
		if($session->get("instatoken")!=null){
			
			$instapics = $this->get('paulp_jackontour_instaapi')
				->getRecentTag($tag, $session->get("instatoken"));
			
			return array("instapics" => $instapics, "instatag" => $tag);
		} 
		else { 
			$url = $this->get('paulp_jackontour_instaapi')
				->getAuthorizeUrl($this->generateUrl('paulp_jackontour_instaapi_instaaccess', array('tag' => $tag), UrlGeneratorInterface::ABSOLUTE_URL));
			return $this->redirect($url);
		}
	}
	
	/**
	 * @Route("/instatags/{tag}", defaults={"tag" = "jack"}, name="instatags")
	 * @Template()
	 */
	public function instatagsAction(Request $request, $tag = 'jack')
	{
		$session = $request->getSession();
		
		if($session->get("instatoken")!=null){
			$instatags = $this->get('paulp_jackontour_instaapi')
				->getTagList($tag, $session->get("instatoken"));
		}
	
		return array('instatags' => $instatags);
	}
	
}
