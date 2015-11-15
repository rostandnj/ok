<?php

namespace Sdz\ATIBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Produit_Magasin
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Sdz\ATIBundle\Entity\Produit_MagasinRepository")
 */
class Produit_Magasin
{
    
	/**
    * @ORM\OneToOne(targetEntity="Sdz\ATIBundle\Entity\Magasin",cascade={"persist"})
    * @ORM\JoinColumn(nullable=false)
	*/
	private $magasin;
	
	/**
    * @ORM\OneToOne(targetEntity="Sdz\ATIBundle\Entity\Produit",inversedBy="produitmagasin")
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
     */
    private $quantite;


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