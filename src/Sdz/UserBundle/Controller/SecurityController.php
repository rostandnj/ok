<?php

namespace Sdz\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Sdz\ATIBundle\Entity\Produit;
use Symfony\Component\Security\Core\SecurityContextInterface;


class SecurityController extends Controller

{
  public function loginAction(Request $request)

  {

    $session = $request->getSession();
    $user= $this->get('security.context')->getToken()->getUser();
    
    if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) 
    { 
        if ($user->getRoles())
        {
          return $this->redirect($this->generateUrl('sdz_ati_gestion'));   
        }
        
    }


    if ($request->attributes->has(SecurityContextInterface::AUTHENTICATION_ERROR)) 
    {
        $error = $request->attributes->get(SecurityContextInterface::AUTHENTICATION_ERROR);
    } 
    elseif (null !== $session && $session->has(SecurityContextInterface::AUTHENTICATION_ERROR)) 
    {
        $error = $session->get(SecurityContextInterface::AUTHENTICATION_ERROR);
        $session->remove(SecurityContextInterface::AUTHENTICATION_ERROR);
    } 
    else 
    {
        $error = null;
    }

    // last username entered by the user
    $lastUsername = (null === $session) ? '' : $session->get(SecurityContextInterface::LAST_USERNAME);

    return $this->render('SdzUserBundle:Security:login.html.twig',array('last_username' => $lastUsername,'error'=> $error,));
    }
     
}