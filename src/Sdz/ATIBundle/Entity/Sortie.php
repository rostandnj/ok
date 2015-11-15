<?php

namespace Sdz\ATIBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sortie
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Sdz\ATIBundle\Entity\SortieRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Sortie
{
    /**
    * @ORM\ManyToOne(targetEntity="Sdz\ATIBundle\Entity\Produit")
    * @ORM\JoinColumn(nullable=false,onDelete="CASCADE")
	*/
	private $produit;
	
	/**
    * @ORM\ManyToOne(targetEntity="Sdz\UserBundle\Entity\User")
    * @ORM\JoinColumn(nullable=false)
	*/
	private $user;
	
	public function __construct()
    {
        $this->date = new \DateTime();
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
    * @ORM\ManyToOne(targetEntity="Sdz\ATIBundle\Entity\Magasin")
    * @ORM\JoinColumn(nullable=false,onDelete="CASCADE")
    */
    private $magasin;

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
     * @var string
     *
     * @ORM\Column(name="observation", type="text", nullable=false)
     */
    private $observation;

    /**
     * Set observation
     *
     * @param string $observation
     * @return Entree
     */
    public function setObservation($observation)
    {
        $this->observation = $observation;
    
        return $this;
    }

    /**
     * Get observation
     *
     * @return string 
     */
    public function getObservation()
    {
        return $this->observation;
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
     * @return Sortie
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
     * @return Sortie
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
     * Set produit
     *
     * @param \Sdz\ATIBundle\Entity\Produit $produit
     * @return Sortie
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

    /**
     * Set user
     *
     * @param \Sdz\UserBundle\Entity\User $user
     * @return Entree
     */
    public function setUser(\Sdz\UserBundle\Entity\User $user)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \Sdz\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }


    /**
     * Set magasin
     *
     * @param \Sdz\ATIBundle\Entity\Magasin $magasin
     * @return Entree
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
}