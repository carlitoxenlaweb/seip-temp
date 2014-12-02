<?php

namespace Pequiven\ArrangementProgramBundle\Model;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Manejador del programa de gestion
 *
 * @author Carlos Mendoza<inhack20@gmail.com>
 */
class ArrangementProgramManager implements ContainerAwareInterface
{
    private $container;
    
    /**
     * Evalua si el usuario logeado tiene permisos para revisar el programa de gestion
     * @param ArrangementProgram $entity
     * @return boolean
     */
    public function isAllowToReview(ArrangementProgram $entity) {
        $configuration = $entity->getTacticalObjective()->getGerencia()->getConfiguration();
        $valid = false;
        if(!$configuration){
            return $valid;
        }
        $user = $this->getUser();
        
        foreach ($configuration->getArrangementProgramUserToRevisers() as $userToReviser) {
            if($user === $userToReviser){
                $valid = true;
                break;
            }
        }
        return $valid;
    }
    
    /**
     * Evalua si el usuario logeado tiene permisos para aprobar el programa de gestion
     * @param ArrangementProgram $entity
     * @return boolean
     */
    public function isAllowToApprove(ArrangementProgram $entity) {
        $configuration = $entity->getTacticalObjective()->getGerencia()->getConfiguration();
        $valid = false;
        if(!$configuration){
            return $valid;
        }
        if($entity->getStatus() == ArrangementProgram::STATUS_REJECTED || $entity->getStatus() == ArrangementProgram::STATUS_APPROVED){
            return $valid;
        }
        $user = $this->getUser();
        
        if($entity->getType() === ArrangementProgram::TYPE_ARRANGEMENT_PROGRAM_TACTIC 
            && $configuration->getArrangementProgramUsersToApproveTactical()->contains($user) === true){
            $valid = true;
        }
        if($entity->getType() === ArrangementProgram::TYPE_ARRANGEMENT_PROGRAM_OPERATIVE 
            && $configuration->getArrangementProgramUsersToApproveOperative()->contains($user) === true){
            $valid = true;
        }
        return $valid;
    }
    
    /**
     * Evalua si el usuario logeado tiene permisos para enviar el programa de gestion a revision
     * @param ArrangementProgram $entity
     * @return boolean
     */
    public function isAllowToSendToReview(ArrangementProgram $entity) {
        $user = $this->getUser();
        $valid = false;
        if( ($entity->getStatus() === ArrangementProgram::STATUS_DRAFT) &&
                ($entity->getCreatedBy() === $user || $this->isAllowToReview($entity) === true || $this->isAllowToApprove($entity) === true) 
            ){
            $valid = true;
        }

        return $valid;
    }
    
    /**
     * Evalua si el usuario logeado tiene permisos para regresar el programa de gestion a borrador
     * @param ArrangementProgram $entity
     * @return boolean
     */
    public function isAllowToSendToDraft(ArrangementProgram $entity) {
        $user = $this->getUser();
        $valid = false;
        if( ($entity->getStatus() === ArrangementProgram::STATUS_IN_REVIEW) &&
                ($entity->getCreatedBy() === $user || $this->isAllowToReview($entity) === true || $this->isAllowToApprove($entity) === true) 
            ){
            $valid = true;
        }

        return $valid;
    }
    
    /**
     * Evalua si el usuario logeado tiene permisos para regresar el programa de gestion a borrador
     * @param ArrangementProgram $entity
     * @return boolean
     */
    public function isAllowReturnToReview(ArrangementProgram $entity) {
        $user = $this->getUser();
        $valid = false;
        if( ($entity->getStatus() === ArrangementProgram::STATUS_REVISED) &&
                ($entity->getCreatedBy() === $user || $this->isAllowToReview($entity) === true || $this->isAllowToApprove($entity) === true) 
            ){
            $valid = true;
        }

        return $valid;
    }
    
    /**
     * Evalua si el usuario logueado tiene permisos para actualizar el programa
     * @param type $entity
     * @throws type
     */
    public function hasPermissionToUpdate(ArrangementProgram $entity) {
        //Security check
        $permission = true;
        $user = $this->getUser();
        if($entity->getCreatedBy() !== $user && $this->isAllowToApprove($entity) === false && $this->isAllowToReview($entity) === false){
            $permission = false;
        }
        if($entity->getStatus() === ArrangementProgram::STATUS_APPROVED || $entity->getStatus() === ArrangementProgram::STATUS_REJECTED){
            $permission = false;
        }
        return $permission;
    }
    
    /**
     * Evalua si el usuario logueado tiene permisos para actualizar el programa
     * @param type $entity
     * @throws type
     */
    public function hasPermissionToAddComment(ArrangementProgram $entity) {
        //Security check
        $permission = true;
        $user = $this->getUser();
        if($entity->getCreatedBy() !== $user && $this->isAllowToApprove($entity) === false && $this->isAllowToReview($entity) === false
                && $this->isAllowToNotity($entity) === false){
            $permission = false;
        }
        if($entity->getStatus() === ArrangementProgram::STATUS_REJECTED){
            $permission = false;
        }
        return $permission;
    }
    
    /**
     * Verifica que se pueba aprobar un programa de gestion
     * @param \Pequiven\ArrangementProgramBundle\Model\ArrangementProgram $entity
     * @return boolean
     */
    public function isYouCanApprove(ArrangementProgram $entity)
    {
        $summary = $entity->getSummary();
        $valid = false;
        if(bccomp($summary['advancesPlanned'], 100) == 0){
            $valid = true;
        }
        
        return $valid;
    }
    
    /**
     * Evalua si el usuario logeado tiene permisos para notificar avancess en el programa de gestion
     * @param ArrangementProgram $entity
     * @return boolean
     */
    public function isAllowToNotity(ArrangementProgram $entity) {
        $configuration = $entity->getTacticalObjective()->getGerencia()->getConfiguration();
        $valid = false;
        if (!$configuration) {
            return $valid;
        }
        $user = $this->getUser();

        if ($configuration->getArrangementProgramUsersToNotify()->contains($user) === true && $entity->getStatus() == ArrangementProgram::STATUS_APPROVED) {
            $valid = true;
        }
        return $valid;
    }

    /**
     * Evalua si el usuario logueado tiene permisos para notificar en el programa
     * @param type $entity
     * @throws type
     */
    public function hasPermissionToNotify(ArrangementProgram $entity) {
        //Security check
        $permission = true;
        $user = $this->getUser();
        if ($entity->isNotificable() === false || 
            ($this->isAllowToNotity($entity) === false && 
                $this->isAllowToApprove($entity) === false && 
                $this->isAllowToReview($entity) === false && 
                $entity->getCreatedBy() !== $user) === true ) {
            $permission = false;
        }
        return $permission;
    }

    /**
     * Evalua si el usuario logueado tiene permisos para notificar en el programa
     * @param type $entity
     * @throws type
     */
    public function hasPermissionToPlanned(ArrangementProgram $entity) {
        //Security check
        $user = $this->getUser();
        $permission = true;
        if (
                $entity->isPlanneable() === false || 
                ($this->isAllowToNotity($entity) === false && 
                        $this->isAllowToApprove($entity) == false && 
                        $this->isAllowToReview($entity) == false && 
                        $entity->getCreatedBy() !== $user) === true) {
            $permission = false;
        }
        return $permission;
    }
    
    /**
     * Evalua si tiene permiso para eliminar el programa de gestion
     * 
     * @param \Pequiven\ArrangementProgramBundle\Model\ArrangementProgram $entity
     * @return boolean
     */
    function isAllowToDelete(ArrangementProgram $entity) {
         //Security check
        $user = $this->getUser();
        $valid = false;
        if(($entity->getCreatedBy() === $user && $entity->getStatus() == ArrangementProgram::STATUS_DRAFT) || $this->getSecurityConext()->isGranted('ROLE_SUPER_ADMIN')){
            $valid = true;
        }
        return $valid;
    }
    
    /**
     * Get a user from the Security Context
     *
     * @return mixed
     *
     * @throws \LogicException If SecurityBundle is not available
     *
     * @see Symfony\Component\Security\Core\Authentication\Token\TokenInterface::getUser()
     */
    public function getUser()
    {
        if (!$this->container->has('security.context')) {
            throw new \LogicException('The SecurityBundle is not registered in your application.');
        }

        if (null === $token = $this->container->get('security.context')->getToken()) {
            return;
        }

        if (!is_object($user = $token->getUser())) {
            return;
        }

        return $user;
    }
    
    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }
    
    /**
     * 
     * @return \Symfony\Component\Security\Core\SecurityContext
     * @throws \LogicException
     */
    private function getSecurityConext()
    {
        if (!$this->container->has('security.context')) {
            throw new \LogicException('The SecurityBundle is not registered in your application.');
        }

        return $this->container->get('security.context');
    }
}
