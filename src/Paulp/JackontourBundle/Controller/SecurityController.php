<?php

namespace Paulp\JackontourBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
	/**
	 * @Route("/login")
	 * @Template()
	 */
	public function loginAction(Request $request)
	{
		$authenticationUtils = $this->get('security.authentication_utils');		
		// get the login error if there is one
		$error = $authenticationUtils->getLastAuthenticationError();
		
		return $this->render(
			'PaulpJackontourBundle:Tour:show.html.twig',
			array('error' => $error)
		);
	}
	
	/**
	 * @Route("/logout", name="logout")
	 * @Template()
	 */
	public function logoutAction(Request $request){}
	
	/**
	 * @Route("/login_check", name="login_check")
	 */
	public function loginCheckAction(){}
}
