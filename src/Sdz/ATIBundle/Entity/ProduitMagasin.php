<?php

namespace Sdz\ATIBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ProduitMagasin
 *
 * @ORM\Table(name="produitmagasin")
 * @ORM\Entity(repositoryClass="Sdz\ATIBundle\Entity\ProduitMagasinRepository")
 */
class ProduitMagasin
{
    
	/**
    * @ORM\ManyToOne(targetEntity="Sdz\ATIBundle\Entity\Magasin",inversedBy="produitmagasin")
    * @ORM\JoinColumn(nullable=false)
	*/
	private $magasin;
	
	/**
    * @ORM\ManyToOne(targetEntity="Sdz\ATIBundle\Entity\Produit",inversedBy="produitmagasin")
    * @ORM\JoinColumn(nullable=false)
	*/
	private $produit;
	
	/**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantite", type="integer")
     * @Assert\Range(min=0)
     */
    private $quantite;

    private $var;
    /**
     * @Assert\Length(min=7,max=50)
     */
    private $observation;

    public function setObservation($observation)
    {
        $this->observation = $observation;
    
        return $this;
    }

    
    public function getObservation()
    {
        return $this->observation;
    }

    /**
     * Get var
     *
     * @Assert\Range(min=1)
     * @return integer 
     */
    public function getVar()
    {
        return $this->var;
    }

    /**
     * Set var
     *
     * @param integer $var
     * @return Produit_Magasin
     */
    public function setVar($var)
    {
        $this->var = $var;
    
        return $this;
    }

    private $vars;

    /**
     * Get vars
     *
     * @Assert\Range(min=1)
     * @return integer 
     */
    public function getVars()
    {
        return $this->vars;
    }

    /**
     * Set vars
     *
     * @param integer $vars
     * @return Produit_Magasin
     */
    public function setVars($vars)
    {
        $this->vars = $vars;
    
        return $this;
    }



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
     * Set date
     *
     * @param \DateTime $date
     * @return Produit_Magasin
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
     * Set quantite
     *
     * @param integer $quantite
     * @return Produit_Magasin
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;
    
        return $this;
    }

    /**
     * Get quantite
     *
     * @return integer 
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * Set magasin
     *
     * @param \Sdz\ATIBundle\Entity\Magasin $magasin
     * @return Produit_Magasin
     */
    public function setMagasin(\Sdz\ATIBundle\Entity\Magasin $magasin)
    {
        $this->magasin = $magasin;
    
        return $this;
    }

    /**
     * Get magasin
     *
     * @return \Sdz\ATIBundle\Entity\Magasin 
     */
    public function getMagasin()
    {
        return $this->magasin;
    }

    /**
     * Set produit
     *
     * @param \Sdz\ATIBundle\Entity\Produit $produit
     * @return Produit_Magasin
     */
    public function setProduit(\Sdz\ATIBundle\Entity\Produit $produit)
    {
        $this->produit = $produit;
    
        return $this;
    }

    /**
     * Get produit
     *
     * @return \Sdz\ATIBundle\Entity\Produit 
     */
    public function getProduit()
    {
        return $this->produit;
    }
}