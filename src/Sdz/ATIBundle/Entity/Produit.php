<?php

namespace Sdz\ATIBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Produit
 *
 * @ORM\Table(name="produits")
 * @ORM\Entity(repositoryClass="Sdz\ATIBundle\Entity\ProduitRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Produit
{
    
	/**
    * @ORM\OneToMany(targetEntity="Sdz\ATIBundle\Entity\ProduitMagasin",mappedBy="produit",cascade={"persist", "remove"})
    */
    private $produitmagasin;
    

    /**
    * @ORM\OneToOne(targetEntity="Sdz\ATIBundle\Entity\Picture",cascade={"persist", "remove"})
    */
	
	private $picture;

    private $nbp;

    public function setNbp()
    {
        $this->nbp = $nbp;
        return $this;
    }

    public function getNpb()
    {
        return $this->nbp;
    }
	
	
	public function __construct()
    {
        $this->date = new \DateTime();
		$this->statut = true;
        $this->nbp = 0;
        $this->qtet = 0;
        $this->qted = 0;

       $this->produitmagasin = new \Doctrine\Common\Collections\ArrayCollection();
    }
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
     * @ORM\Column(name="nom", type="string", length=100,)
     * @Assert\Length(min=3)
     * @Assert\NotBlank()
     */
	private $nom;
    
	 /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     * @Assert\DateTime()
     */
    private $date;

    /**
     * @var integer
     *
     * @ORM\Column(name="qtet", type="integer",nullable=true)
     */
    private $qtet;

    /**
     * @var integer
     *
     * @ORM\Column(name="qted", type="integer",nullable=true)
     */
    private $qted;

    /**
     * Set qtet
     *
     * @param integer $qtet
     * @return Produit
     */
    public function setQtet($qtet)
    {
        $this->qtet = $qtet;
    
        return $this;
    }

    /**
     * Get qtet
     *
     * @return integer 
     */
    public function getQtet()
    {
        return $this->qtet;
    }

    /**
     * Set qted
     *
     * @param integer $qted
     * @return Produit
     */
    public function setQted($qted)
    {
        $this->qted = $qted;
    
        return $this;
    }

    /**
     * Get qted
     *
     * @return integer 
     */
    public function getQted()
    {
        return $this->qted;
    }
	 
    /**
     * @var string
     *
     * @ORM\Column(name="caracteristique", type="text", nullable=true)
     */
    private $caracteristique;

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="boolean")
     */
    private $statut;


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
     * Set nom
     *
     * @param string $nom
     * @return Produit
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    
        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set caracteristique
     *
     * @param string $caracteristique
     * @return Produit
     */
    public function setCaracteristique($caracteristique)
    {
        $this->caracteristique = $caracteristique;
    
        return $this;
    }

    /**
     * Get caracteristique
     *
     * @return string 
     */
    public function getCaracteristique()
    {
        return $this->caracteristique;
    }

    /**
     * Set statut
     *
     * @param string $statut
     * @return Produit
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;
    
        return $this;
    }

    /**
     * Get statut
     *
     * @return string 
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Produit
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set picture
     *
     * @param \Sdz\ATIBundle\Entity\Picture $picture
     * @return Produit
     */
    public function setPicture(\Sdz\ATIBundle\Entity\Picture $picture = null)
    {
        $this->picture = $picture;
    
        return $this;
    }

    /**
     * Get picture
     *
     * @return \Sdz\ATIBundle\Entity\Picture 
     */
    public function getPicture()
    {
        return $this->picture;
    }


    /**
     * Set produitmagasin
     *
     * @param \Sdz\ATIBundle\Entity\ProduitMagasin $produitmagasin
     * @return ProduitMagasin
     */
    public function setProduitmagasin(\Sdz\ATIBundle\Entity\ProduitMagasin $produitmaga = null)
    {
        
        $this->produitmagasin[] = $produitmaga;
        
        return $this;
    }

    /**
     * Get produitmagasin
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProduitmagasin()
    {
        return $this->produitmagasin;
    }

    
}