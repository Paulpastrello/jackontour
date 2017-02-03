<?php

namespace Paulp\JackontourBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Tappe
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Tappe
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nome", type="string", length=32)
     * @Assert\NotBlank(
     	groups={"Tappe","step1"},
     	message = "Nome obbligatorio"
     	)
     */
    private $nome;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=128, nullable=true)
     * @Assert\Email(
     	message = "Formato email non valido"
     	)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="addr", type="string", length=256)
     * @Assert\NotBlank(
        groups={"Tappe","step1"},
     	message = "Indirizzo obbligatorio"
     	)
     */
    private $addr;
    
    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=64, nullable=true)
     */
    private $city;
    
    /**
     * @var string
     *
     * @ORM\Column(name="latlng", type="string", length=64)
     * @Assert\NotBlank(
        groups={"Tappe","step1"},
     	message = "Posizione non riconosciuta. Controlla l'indirizzo o seleziona un punto sulla mappa."
     	)
     */
    private $latlng;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="data", type="datetime")
     */
    private $data;
    
    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=1, options={"default":"C"})
     */
    private $status = 'C';
    
    /**
     * @var string
     *
     * @ORM\Column(name="tweet", type="string", length=160, nullable=true)
     * @Assert\Length(
           max = 160,
           maxMessage = "Il messaggio puo' contenere al massimo {{ limit }} charatteri"
      )
     */
    private $tweet;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nome
     *
     * @param string $nome
     * @return Tappe
     */
    public function setNome($nome)
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * Get nome
     *
     * @return string 
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Tappe
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set addr
     *
     * @param string $addr
     * @return Tappe
     */
    public function setAddr($addr)
    {
        $this->addr = $addr;

        return $this;
    }

    /**
     * Get addr
     *
     * @return string 
     */
    public function getAddr()
    {
        return $this->addr;
    }
    
    /**
     * Set city
     *
     * @param string $city
     * @return Tappe
     */
    public function setCity($city)
    {
    	$this->city = $city;
    
    	return $this;
    }
    
    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
    	return $this->city;
    }
    
    /**
     * Set latlng
     *
     * @param string $latlng
     * @return Tappe
     */
    public function setLatlng($latlng)
    {
    	$this->latlng = $latlng;
    
    	return $this;
    }
    
    /**
     * Get latlng
     *
     * @return string
     */
    public function getLatlng()
    {
    	return $this->latlng;
    }
    
    /**
     * Set status
     *
     * @param string $status
     * @return Tappe
     */
    public function setStatus($status)
    {
    	$this->status = $status;
    
    	return $this;
    }
    
    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
    	return $this->status;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreateAtValue()
    {
        $this->data = new \DateTime();
    }

    /**
     * Get data
     *
     * @return \DateTime 
     */
    public function getData()
    {
        return $this->data;
    }
    
    /**
     * Set tweet
     *
     * @param string $nome
     * @return Tappe
     */
    public function setTweet($tweet)
    {
    	$this->tweet = $tweet;
    
    	return $this;
    }
    
    /**
     * Get tweet
     *
     * @return string
     */
    public function getTweet()
    {
    	return $this->tweet;
    }
}
