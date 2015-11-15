<?php

namespace Sdz\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Sdz\UserBundle\Entity\UserRepository")
 */
class User implements AdvancedUserInterface, EquatableInterface

{
    /**
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
    private $id;

    /**
    * @ORM\Column(name="username", type="string", length=50, unique=true)
    */
    private $username;

    /**
    * @ORM\Column(name="password", type="string", length=255)
    * @Assert\Length(min=7)
    */
    private $password;

    /**
    * @ORM\Column(name="salt", type="string", length=255)
    */
    private $salt;

    /**
    * @ORM\ManytoMany(targetEntity="Sdz\UserBundle\Entity\Role",cascade={"persist"})
    */
    private $roles;

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
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    
    /**
 * Set isActive
 *
 * @param boolean $isActive
 * @return User
 */
public function setIsActive($isActive)
{
    $this->isActive = $isActive;

    return $this;
}

/**
 * Get isActive
 *
 * @return boolean 
 */
public function getIsActive()
{
    return $this->isActive;
}



    public function __construct()
    {
        $this->isActive = true;
        
        $this->roles = new ArrayCollection();
    }

    /**
    * @inheritDoc UserInterface
    */
    public function getRoles()
    {
        return $this->roles->toArray();
    }

    public function setRoles($role)
    {
         $this->roles[] = $role;

        return $this;
    }

    public function eraseCredentials()
    {
        
    }

    public function isEqualTo(UserInterface $user)
    {
        if (!$user instanceof WebserviceUser) {
            return false;
        }

        if ($this->password !== $user->getPassword()) {
            return false;
        }

        if ($this->salt !== $user->getSalt()) {
            return false;
        }

        if ($this->username !== $user->getUsername()) {
            return false;
        }

        return true;
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
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;
    
        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    
        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    public function addRole(\Sdz\UserBundle\Entity\Role $role)
    {
        $this->roles[] = $role;
    }

    public function removeRole(\Sdz\UserBundle\Entity\Role $role)
    {
        $this->roles->removeElement($role);
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

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive;
    }
    

}