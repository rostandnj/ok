<?php

namespace Sdz\UserBundle\Handler;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;

/**
 * @ORM\Entity(repositoryClass="Sdz\UserBundle\Entity\UserRepository")
 */
class LogoutHandler implements LogoutSuccessHandlerInterface

{
    protected $elm =  EventLogManager;
 
    

    /**
     * Opération réalisées suite à la deconnexion.
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function onLogoutSuccess(Request $request)
    {
        $this->elm->add('Log de deconnexion');
        $session = $request->getSession();
        $session->invalidate();
 
        $response = new RedirectResponse($request->headers->get('referer'));
        return $response;
    }

}