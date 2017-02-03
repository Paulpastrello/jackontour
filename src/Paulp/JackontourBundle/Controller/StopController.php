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

class StopController extends Controller
{

	/**
	 * @Route("/remove/{id}", name="remove_stop")
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
<<<<<<< HEAD
		$session->remove('bababa');
		$session->remove('sesstap');
		
		$tappe = new Tappe();
		$form = $this->createForm($this->get('paulp_jackontour_tappetype'), $tappe, 
				array(
						'action' => $this->generateUrl('paulp_jackontour_stop_add'),
						'validation_groups' => array('step1')
				)
				);
		$form->handleRequest($request);
		
		if ($form->isValid()) {
			$session->set('sesstap', $tappe);
			
			$form = $this->createForm($this->get('paulp_jackontour_tappetype'), $tappe,
					array(
						'action' => $this->generateUrl('paulp_jackontour_stop_confirm'),
						'step' => 2
					)
			);
			
// 			$this->addFlash(
// 				'info',
// 				'Controlla sulla mappa il tuo indirizzo: " '.$tappe->getAddr().' ". Se � corretto conferma la posizione. Altrimenti ti prego di riprovare.'
// 			);

			return $this->render("PaulpJackontourBundle:Stop:completeform.html.twig", array('form' => $form->createView()));
		} else {
			return $this->render("PaulpJackontourBundle:Tour:show.html.twig", array('form' => $form->createView()));
		}
=======
		
		$tappe = new Tappe();
		$form = $this->createForm($this->get('paulp_jackontour_tappetype'), $tappe, 
				array(
						'action' => $this->generateUrl('paulp_jackontour_stop_add'),
						'validation_groups' => array('step1')
				)
				);
		$form->handleRequest($request);
		
		if ($form->isValid()) {
			$session->set('sesstap', $tappe);
			
			$form = $this->createForm($this->get('paulp_jackontour_tappetype'), $tappe,
					array(
						'action' => $this->generateUrl('paulp_jackontour_stop_confirm'),
						'step' => 2
					)
			);
			
			$this->addFlash(
				'info',
				'Controlla sulla mappa il tuo indirizzo: " '.$tappe->getAddr().' ". Se � corretto conferma la posizione. Altrimenti ti prego di riprovare.'
			);
		}
		return $this->render("PaulpJackontourBundle:Stop:completeform.html.twig", array('form' => $form->createView()));
>>>>>>> refs/remotes/origin/master
	}
	
	/**
	 * @Route("/confirm")
	 * @Method("POST")
	 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
	 * @Template()
	 */
	public function confirmAction(Request $request)
	{
		$session = $request->getSession();	
		
		$tappe = $session->get('sesstap');
		$form = $this->createForm($this->get('paulp_jackontour_tappetype'), $tappe,
				array(
							'action' => $this->generateUrl('paulp_jackontour_stop_confirm'),
							'step' => 2
					)
		);
		$form->handleRequest($request);
		
		if ($form->get('cancel')->isClicked()) {
			$form = $this->createForm($this->get('paulp_jackontour_tappetype'), $tappe,
					array(
							'action' => $this->generateUrl('paulp_jackontour_stop_add'),
							'step' => 1
					)
			);
			$session->remove('sesstap');
			return $this->render("PaulpJackontourBundle:Tour:show.html.twig", array('form' => $form->createView()));
		} else if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($tappe);
			$em->flush();
			
			$session->set('lastId', $tappe->getId());
			
			$this->addFlash(
					'success',
					'Grazie per aver inserito la posizione. Il tuo indirizzo &egrave; nella lista!'
			);
		
			$session->remove('sesstap');
			return $this->redirectToRoute("home");
		}
		return $this->render("PaulpJackontourBundle:Stop:completeform.html.twig", array('form' => $form->createView()));
	}

}
