<?php

namespace Sdz\ATIBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Personnel
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Sdz\ATIBundle\Entity\PersonnelRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Personnel
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
     * @ORM\Column(name="nom", type="string", length=50)
     * @Assert\Length(min=7)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="no_piece_ident", type="string", length=20)
     * @Assert\Range(min=5)
     */
    private $noPieceIdent;

    /**
    * @ORM\OneToOne(targetEntity="Sdz\UserBundle\Entity\User",mappedBy="personnel",cascade={"persist", "remove"})
    */
    private $user;


    /**
     * @var string
     *
     * @ORM\Column(name="poste", type="string", length=50)
     * @Assert\Length(min=5)
     */
    private $poste;

    /**
     * @var string
     *
     * @ORM\Column(name="ville_residence", type="string", length=50)
     * @Assert\Length(min=3)
     */
    private $villeResidence;


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
     * @return Personnel
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
     * Set cni
     *
     * @param string $cni
     * @return Personnel
     */
    public function setCni($cni)
    {
        $this->cni = $cni;
    
        return $this;
    }

    /**
     * Get cni
     *
     * @return string 
     */
    public function getCni()
    {
        return $this->cni;
    }

    /**
     * Set poste
     *
     * @param string $poste
     * @return Personnel
     */
    public function setPoste($poste)
    {
        $this->poste = $poste;
    
        return $this;
    }

    /**
     * Get poste
     *
     * @return string 
     */
    public function getPoste()
    {
        return $this->poste;
    }

    /**
     * Set villeResidence
     *
     * @param string $villeResidence
     * @return Personnel
     */
    public function setVilleResidence($villeResidence)
    {
        $this->villeResidence = $villeResidence;
    
        return $this;
    }

    /**
     * Get villeResidence
     *
     * @return string 
     */
    public function getVilleResidence()
    {
        return $this->villeResidence;
    }

    /**
     * Set no_piece_ident
     *
     * @param string $noPieceIdent
     * @return Personnel
     */
    public function setNoPieceIdent($noPieceIdent)
    {
        $this->noPieceIdent = $noPieceIdent;
    
        return $this;
    }

    /**
     * Get no_piece_ident
     *
     * @return string 
     */
    public function getNoPieceIdent()
    {
        return $this->noPieceIdent;
    }
}