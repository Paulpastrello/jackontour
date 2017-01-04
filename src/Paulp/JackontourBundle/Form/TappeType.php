<?php

namespace Paulp\JackontourBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Paulp\JackontourBundle\Entity\Tappe;

class TappeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

    	$tappe = new Tappe();
    	
        $builder
            ->add('nome', 'text')
            ->add('email', 'email', array('required' => false))
            ->add('addr', 'text')
            ->add('latlng', 'hidden')            
            ->add('save', 'submit', array('label' => 'Crea post'))
            ;
        
        $builder 
        	->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) use ($tappe) {
        		$tappe = $event->getData();
        		$result = $this->callGoogleApis($tappe);
        		
        		if($result!=null){
        			$location = $result['geometry']['location'];
        			$latlng = $location['lat'].','.$location['lng'];
        			$formatted_address = $result['formatted_address'];
        			$address_components = $result['address_components'];
        			
        			$city = "";
        			for ($c = 0; $c < count($address_components); $c++) {
        				if($address_components[$c]['types'][0]==='administrative_area_level_3')
        					$city = $address_components[$c]['long_name'];
        				else if($address_components[$c]['types'][0]==='locality'){
        					$city = $address_components[$c]['long_name'];
        					break;
        				}
        			}
        			
        			$tappe->setAddr($formatted_address);
        			$tappe->setLatlng($latlng);
        			$tappe->setCity($city);
        		}
            })
        ;
    }
    
    public function callGoogleApis(Tappe $tappe){
		if($tappe->getLatlng()===null || $tappe->getLatlng()===''){
    		$cityclean = str_replace (" ", "+", $tappe->getAddr());
    		$details_url = "http://maps.googleapis.com/maps/api/geocode/json?address=" . $cityclean . "&sensor=false";
		} else {
    		$details_url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=" . $tappe->getLatlng() . "&sensor=false";
		}
    	
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $details_url);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	$geoloc = json_decode(curl_exec($ch), true);

    	if(count($geoloc['results'])>0){
    		return $geoloc['results'][0];
		} 
    	else return null;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Paulp\JackontourBundle\Entity\Tappe'
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
