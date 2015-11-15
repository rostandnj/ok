<?php

namespace Sdz\ATIBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Statistique
 * @ORM\Entity(repositoryClass="Sdz\ATIBundle\Entity\StatistiqueRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Statistique
{
     /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    public function __construct()
    {
        $this->date = new \DateTime();
    }

    /**
    * @ORM\ManyToOne(targetEntity="Sdz\ATIBundle\Entity\Produit")
    * @ORM\JoinColumn(nullable=false,onDelete="CASCADE")
    */
    private $produit;



   
    /**
     * @var \DateTime
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var integer
     * @ORM\Column(name="mois", type="integer")
     */
    private $mois;

    /**
     * @var integer
     *
     * @ORM\Column(name="anne", type="integer")
     */
    private $annee;

    /**
     * @var integer
     *
     * @ORM\Column(name="entree", type="integer")
     */
    private $entree;

    /**
     * @var integer
     *
     * @ORM\Column(name="sortie", type="integer")
     */
    private $sortie;

    
    private $var;

    /**
     * @var float
     *
     * @ORM\Column(name="freqvente", type="float")
     */
    private $freqvente;


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
     * @return Statistique
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
     * Set mois
     *
     * @param integer $mois
     * @return Statistique
     */
    public function setMois($mois)
    {
        $this->mois = $mois;
    
        return $this;
    }

    /**
     * Get mois
     *
     * @return integer 
     */
    public function getMois()
    {
        return $this->mois;
    }

    /**
     * Set annee
     *
     * @param integer $annee
     * @return Statistique
     */
    public function setAnnee($annee)
    {
        $this->annee = $annee;
    
        return $this;
    }

    /**
     * Get annee
     *
     * @return integer 
     */
    public function getAnnee()
    {
        return $this->annee;
    }

    /**
     * Set entree
     *
     * @param integer $entree
     * @return Statistique
     */
    public function setEntree($entree)
    {
        $this->entree = $entree;
    
        return $this;
    }

    /**
     * Get entree
     *
     * @return integer 
     */
    public function getEntree()
    {
        return $this->entree;
    }

    /**
     * Set sortie
     *
     * @param integer $sortie
     * @return Statistique
     */
    public function setSortie($sortie)
    {
        $this->sortie = $sortie;
    
        return $this;
    }

    /**
     * Get sortie
     *
     * @return integer 
     */
    public function getSortie()
    {
        return $this->sortie;
    }

    /**
     * Set var
     *
     * @param integer $var
     * @return Statistique
     */
    public function setVar($var)
    {
        $this->var = $var;
    
        return $this;
    }

    /**
     * Get var
     *
     * @return integer 
     */
    public function getVar()
    {
        return $this->var;
    }

    /**
     * Set freqvente
     *
     * @param float $freqvente
     * @return Statistique
     */
    public function setFreqvente($freqvente)
    {
        $this->freqvente = $freqvente;
    
        return $this;
    }

    /**
     * Get freqvente
     *
     * @return float 
     */
    public function getFreqvente()
    {
        return $this->freqvente;
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