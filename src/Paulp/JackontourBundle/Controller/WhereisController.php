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

class WhereisController extends Controller
{
	/**
	 * @Route("/remove/{id}" name="remove")
	 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
	 * @Template()
	 */
	public function removeAction(Request $request, $id = null)
	{
		if($id==null) return $this->redirectToRoute("home");
		
		$session = $request->getSession();
		
		$em = $this->getDoctrine()->getManager();
		$tappe = $em->getRepository('PaulpJackontourBundle:Tappe')->find($id);
		
		if($tappe!=null && $id==$session->get('lastId')){
			$tappe->setStatus('D');
			$em->flush();
			
			$this->addFlash(
					'success',
					'Eliminata la posizione'. $tappe->getAddr()
			);
			$session->remove('lastId');
		} else {
			if($tappe == null) $errMsg = 'Attenzione: Tappa non trovata!';
			elseif($id != $session->get('lastId')) $errMsg = 'WE! Hai cercato di eliminare una Tappa non tua!';
			else $errMsg = 'Errore: impossibile eliminare la tappa'; 
			$this->addFlash(
					'danger',
					$errMsg
			);
		}
		
		return $this->redirectToRoute("home");
	}
	
	/** 
     * @Route("/add")
     * @Method("POST")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Template()
     */
    public function addAction(Request $request)
	{		
		$session = $request->getSession();
		
		$tappe = new Tappe();
		$form = $this->createForm(new TappeType(), $tappe, 
				array('action' => $this->generateUrl('paulp_jackontour_whereis_add'))
				);
		$form->handleRequest($request);
			
		if ($form->isValid()) {
			// esegue alcune azioni, come ad esempio salvare il task nella base dati
			$em = $this->getDoctrine()->getManager();
			$em->persist($tappe);
			$em->flush();
			
			$session->set('lastId', $tappe->getId());
				
			$this->addFlash(
					'success',
					'Hai inserito la posizione " '.$tappe->getAddr().' ". Ti sei accorto di aver sbagliato? No problem, sei ancora in tempo'
			);
			return $this->redirectToRoute("home");
		}
		return $this->render("PaulpJackontourBundle:Whereis:show.html.twig", array('form' => $form->createView()));
	}
	
// 	/**
// 	 * @Route("/add")
// 	 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
// 	 * @Method("POST")
// 	 * @Template()
// 	 */
// 	public function addAction(Request $request)
// 	{
// // 		if (!$request->isXmlHttpRequest()) {
// // 			return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
// // 		}
		
// 		$session = $request->getSession();
// 		if($session->get('tappa')!=null){
// 			$tappe = new Tappe();
// 			$tappe = $session->get('tappa');
			
// 			$em = $this->getDoctrine()->getManager();
// 			$em->persist($tappe);
// 			$em->flush();
	
// 			$this->addFlash(
// 					'success',
// 					'Le modifiche sono state salvate!'
// 			);
			
// 			$session->remove('tappa');
// 		}
		
// // 		$response = new JsonResponse(
// // 				array(
// // 						'result' => 'OK',
// // 						'view' => $this->renderView('PaulpJackontourBundle:Whereis/add:result.html.twig')
// // 				), 200);
		
// // 		return $response;
// 		return $this->redirectToRoute("home");
// 	}
	
// 	/**
// 	 * @Route("/validate")
// 	 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
// 	 * @Method("POST")
// 	 * @Template()
// 	 */
// 	public function validateAction(Request $request)
// 	{
// // 		if (!$request->isXmlHttpRequest()) {
// // 			return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
// // 		}
			
// 		$tappe = new Tappe();
		
// 		$session = $request->getSession();
// 		if($session->get('tappa')!=null){
// 			$tappe = $session->get('tappa');
// 		}
			
// 		$form = $this->createForm(new TappeType(), $tappe,
// 				array('action' => $this->generateUrl('paulp_jackontour_whereis_validate'))
// 		);
// 		$form->handleRequest($request);
			
// 		if ($form->isValid()) {	
// 			$session = $request->getSession();
// 			$session->set('tappa', $tappe);
			
// 			$this->addFlash(
// 					'warning',
// 					'La posizione che stai inserendo è corretta? " '.$tappe->getAddr().' "'
// 			);
// // 			$response = new JsonResponse(
// // 				array(
// // 					'validateResult' => 'OK',
// // 					'view' => $this->renderView('PaulpJackontourBundle:Whereis/add:validate.html.twig')						
// // 				), 200);
// 			return $this->render("PaulpJackontourBundle:Whereis:show.html.twig", 
// 					array('validateResult' => 'OK'));
// 		} 
// // 		else {
// // 			$response = new JsonResponse(
// // 				array(
// // 					'validateResult' => 'KO',
// // 					'errMsg' => $tappe->getNome()==null?'null':$tappe->getNome(),
// // 					'view' => $this->renderView('PaulpJackontourBundle:Whereis/add:form.html.twig',
// // 						array(
// // 							'form' => $form->createView(),
// // 						))), 200);
// // 		}
// // 		return $response;
// 		return $this->render("PaulpJackontourBundle:Whereis:show.html.twig", array('form' => $form->createView()));
// 	}

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
       // crea un task fornendo alcuni dati fittizi per questo esempio
		$tappe = new Tappe();
		$form = $this->createForm(new TappeType(), $tappe, 
			array('action' => $this->generateUrl('paulp_jackontour_whereis_add'))
		);
				
		return array('form' => $form->createView());    
    }

}
