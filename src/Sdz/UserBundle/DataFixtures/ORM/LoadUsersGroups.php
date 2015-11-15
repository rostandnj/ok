<?php


namespace Sdz\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sdz\UserBundle\Entity\User;
use Sdz\UserBundle\Entity\Role;
use Doctrine\ORM\EntityRepository;
use Sdz\ATIBundle\Entity\Personnel;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;


class LoadUsersGroups implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    // Liste des noms de catégorie à ajouter
   

    $role_employe = new Role("ROLE_EMPLOYE");

    $manager->persist($role_employe);
    $manager->flush();

    $role_gestionnaire = new Role("ROLE_GESTIONNAIRE");
    $manager->persist($role_gestionnaire);
    $manager->flush();

    $role_admin = new Role("ROLE_ADMIN");
    $manager->persist($role_admin);
    $manager->flush();

    $role_superadmin = new Role("ROLE_SUPER_ADMIN");
    $manager->persist($role_superadmin);
    $manager->flush();


   
    

    $user = new User();

    $user->setNom("Njomo Rostand");
    $user->setVilleResidence("Yaounde");
    $user->setNoPieceIdent("123456789");
    $user->setPoste("Stagiare");
    $user->setUsername("rostand");
    $user->setSalt(md5(time()));
    $encoder = new MessageDigestPasswordEncoder('sha512',true,10);
    $password = $encoder->encodePassword('rostand',$user->getSalt());
    $user->setPassword($password);
    $user->addRole($role_employe);
    
    $manager->persist($user);
    $manager->flush();

    $user = new User();
     
    


    $user->setNom("Marlone NJ");
    $user->setVilleResidence("Douala");
    $user->setNoPieceIdent("9874563210");
    $user->setPoste("Gestionnaire de credit");
    $user->setUsername("marlone");
    $user->setSalt(md5(time()));
    $encoder = new MessageDigestPasswordEncoder('sha512',true,10);
    $password = $encoder->encodePassword('marlone',$user->getSalt());
    $user->setPassword($password);
    $user->addRole($role_gestionnaire);
    
    $manager->persist($user);
    $manager->flush();

    $user = new User();

    $user->setNom("Rostand  Nj");
    $user->setVilleResidence("Buea");
    $user->setNoPieceIdent("123456789");
    $user->setPoste("Dj internationnal");
    $user->setUsername("rostandnj");
    $user->setSalt(md5(time()));
    $encoder = new MessageDigestPasswordEncoder('sha512',true,10);
    $password = $encoder->encodePassword('rostandnj',$user->getSalt());
    $user->setPassword($password);
    $user->addRole($role_admin);
    
    $manager->persist($user);
    $manager->flush();

  }



    
}
