<?php

namespace Paulp\JackontourBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use Paulp\JackontourBundle\Entity\Tappe;
use Paulp\JackontourBundle\Form\TappeType;

class TourController extends Controller
{

<<<<<<< HEAD
	/**
	 * @Route("/listext/{page}", name="listext")
	 * @Template()
	 */
	public function listextAction($page = 1)
	{
		$pageSize = 25;
		return $this->listAction($page, $pageSize, ($page-1)*$pageSize);
	}
	
    /**
     * @Route("/list/{page}/{pageOffset}", defaults={"pageOffset" = 0})
     * @Template()
     */
    public function listAction($page = 1, $pageSize = 5, $pageOffset = 0)
    {
    	$em = $this->getDoctrine()->getManager();
    	$tappe = $em->getRepository('PaulpJackontourBundle:Tappe')
    	->findBy(array('status' => 'C'), array('data' => 'DESC'), $page * $pageSize + 1, $pageOffset);
    	 
    	return array('tappe' => $tappe, 'pageSize' => $pageSize, 'page' => $page, 'pageOffset' => $pageOffset);    	  
    }

    /**
     * @Route("/", name="home")
     * @Template()
     */
    public function showAction(Request $request)
    {
		// crea un task fornendo alcuni dati fittizi per questo esempio
		$tappe = new Tappe();
		$form = $this->createForm($this->get('paulp_jackontour_tappetype'), $tappe, 
			array('action' => $this->generateUrl('paulp_jackontour_stop_add'))
		);
				
=======
    /**
     * @Route("/list/{page}")
     * @Template()
     */
    public function listAction($page = 1)
    {
    	$pageSize = 5;
      	$em = $this->getDoctrine()->getManager();
    	$tappe = $em->getRepository('PaulpJackontourBundle:Tappe')
				->findBy(array('status' => 'C'), array('data' => 'DESC'), $page * $pageSize + 1, 0);
				
        return array('tappe' => $tappe, 'pageSize' => $pageSize, 'page' => $page);  
    }

    /**
     * @Route("/", name="home")
     * @Template()
     */
    public function showAction(Request $request)
    {
    	$session = $request->getSession();
       // crea un task fornendo alcuni dati fittizi per questo esempio
		$tappe = new Tappe();
		$form = $this->createForm($this->get('paulp_jackontour_tappetype'), $tappe, 
			array('action' => $this->generateUrl('paulp_jackontour_stop_add'))
		);
				
		$session->remove('sesstap');
>>>>>>> refs/remotes/origin/master
		return array('form' => $form->createView());    
    }

}
