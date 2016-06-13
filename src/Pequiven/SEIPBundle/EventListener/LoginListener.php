<?php

namespace Pequiven\SEIPBundle\EventListener;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine; 
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\FOSUserBundle;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\UserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent


class LoginListener implements EventSubscriberInterface
{
    /** @var \Symfony\Component\Security\Core\SecurityContext */
    private $securityContext;

    /** @var \Doctrine\ORM\EntityManager */
    private $em;

    /** @var \Symfony\Component\HttpFoundation\Session\Session */
    private $session;

    /**
     * Constructor
     *
     * @param SecurityContext $securityContext
     * @param Doctrine        $doctrine
     */
    public function __construct(SecurityContext $securityContext, Doctrine $doctrine, Session $session)
    {
        $this->securityContext = $securityContext;
        $this->em = $doctrine->getEntityManager();
        $this->session = $session;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
                FOSUserEvents::SECURITY_IMPLICIT_LOGIN => 'onSecurityImplicitLogin',
                FOSUserEvents::REGISTRATION_COMPLETED=> 'onRegistrationCompleted'
        );
    }

    public function onSecurityImplicitLogin(UserEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();

        if ($this->securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $event->getAuthenticationToken()->getUser();
            // $this->session->set('currentCompanyId', $user->getDefaultConnection());
        }
    }

    //Si el usuario de registra
    public function onRegistrationCompleted(FilterUserResponseEvent $event){
        /* TODO */

        // $user = $event->getAuthenticationToken()->getUser();
        // $request = $event->getRequest();
        // saveLogin($request, $user);
    }

    public function saveLogin($request, $user){
        /* TODO */

        // $login = new UserLogin();
        // $login->setIp($request->getClientIp());
        // $login->setUser($user);

        // $this->em->persist($login);
        // $this->em->flush();
    }
}