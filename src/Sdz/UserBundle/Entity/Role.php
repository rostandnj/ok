<?php

namespace Sdz\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\RoleInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchy;

/**
 * Role
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Sdz\UserBundle\Entity\RoleRepository")
 */
class Role extends RoleHierarchy implements RoleInterface
{
    /**
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
    private $id;

    /**
    * @ORM\Column(name="role", type="string", length=50)
    */
    private $role;

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
     * Set role
     *
     * @param string $role
     * @return Role
     */
    public function setRole($role)
    {
        $this->role = $role;
    
        return $this;
    }


    /**
     * Get role
     *
     * @return string 
     */
    public function getRole()
    {
        return $this->role;
    }

    public function __construct($role)
    {
        $this->role =  $role;
    }
}
