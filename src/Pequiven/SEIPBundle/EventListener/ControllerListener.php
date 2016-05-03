<?php

namespace Pequiven\SEIPBundle\EventListener;

use Pequiven\MasterBundle\Model\ControllerFilters;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

use Tecnocreaciones\Bundle\AjaxFOSUserBundle\Controller\SecurityController;

class ControllerListener
{
	private $doctrine;

	private $session;

	private $storage;

    public function __construct(Registry $doctrine, Session $session, TokenStorage $security)
    {
    	$this->doctrine = $doctrine;
    	$this->session  = $session;
    	$this->storage  = $security->getToken();
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        // var_dump($this->session->get('currentCompanyId'));
        // var_dump($this->session->get('connectionParameter'));
        // die();
        $controller = $event->getController();

        if (!is_array($controller)) {
            return;
        }

        if ($controller[0] instanceof ControllerFilters && !$controller[0] instanceof SecurityController) {
        	$user = $this->storage->getUser();
            // var_dump($this->session->has('currentCompanyId'));
            // var_dump($this->session->get('currentCompanyId'));
            // var_dump(!$this->session->has('currentCompanyId'));
            // var_dump($user);
        	if(!$this->session->has('currentCompanyId')) {
	            $connection = $user->getDefaultConnection();
	            $user->setCurrentCompany($connection->getCompany());
	            $this->session->set('currentCompanyId', $connection->getCompany()->getId());
	        } else {
		        $company = $this->doctrine
		        				->getRepository('PequivenSEIPBundle:CEI\\Company')
		                        ->findOneById($this->session->get('currentCompanyId'));
		        $user->setCurrentCompany($company);
	        }
	    }
        // $user = new User();
        // $company = $this->doctrine
        //                 ->getRepository('PequivenSEIPBundle:CEI\\Company')
        //                 ->findOneByName($this->default);
        // //var_dump($company);
        // $user->setCurrentCompany($company);
	}
}