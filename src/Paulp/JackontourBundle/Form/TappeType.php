<?php

namespace Paulp\JackontourBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Paulp\JackontourBundle\Entity\Tappe;
use Paulp\JackontourBundle\Entity\Geoloc;
use Paulp\JackontourBundle\Service\Googleapi;

class TappeType extends AbstractType
{
	private $gapi;
	
	public function __construct(Googleapi $gapi)
	{
		$this->gapi = $gapi;
	}
	
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$tappe = new Tappe();
    	
        if( $options['step'] == 2 ){
        	$builder
        		->add('nome', 'hidden')
        		->add('latlng', 'hidden')
        		->add('addr', 'text', array('disabled' => true))
        		->add('email', 'text', array('required' => false))
        		->add('tweet', 'textarea', array('required' => false))
        		->add('save', 'submit', array('label' => 'Conferma'))
        		->add('cancel', 'submit', array('label' => 'Modifica'));
        }else {
        	$builder
	        	->add('nome', 'text')
	        	->add('addr', 'text')
	        	->add('latlng', 'hidden')
	        	->add('save', 'submit', array('label' => 'Inserisci'));
        	
        	$builder
	        	->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) use ($tappe) {
	        		$tappe = $event->getData();
	        		$this->fillGeolocFields($tappe);
        	});
        }
                
        	/*->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($tappe) {
        		$tappe = $event->getData();
        		
        		$geoloc = new Geoloc();
        		$geoloc = $this->gapi->callGooglelocation();
        		
        		if($geoloc!=null){
        			$tappe->setLatlng($geoloc->getPosition());
	        		$this->fillGeolocFields($tappe);
        		}
        	})*/
    }
    
    public function fillGeolocFields(Tappe $tappe){
    	if($tappe->getLatlng()!=null && $tappe->getLatlng()!==''){
    		$latlng = $tappe->getLatlng();
    		$geoloc = $this->gapi->getGoogleAddress($latlng);
    	} else {
    		$addr = $tappe->getAddr();
    		$geoloc = $this->gapi->getGoogleCoords($addr);
    	}
    	
    	if($geoloc!=null){
    		$tappe->setAddr($geoloc->getAddress());
    		$tappe->setLatlng($geoloc->getPosition());
    		$tappe->setCity($geoloc->getCity());
    	}
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Tappe::class,
        	'validation_groups' => array('Tappe'),
        	'step' => 1
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'paulp_jackontourbundle_tappe';
    }
}
