<?php

namespace Pequiven\ArrangementProgramBundle\Repository;

use Tecnocreaciones\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Pequiven\SEIPBundle\Entity\Period;
use Pequiven\SEIPBundle\Entity\User;

/**
 * Repositorio de programa de gestion
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArrangementProgramRepository extends EntityRepository
{
    /**
     * Retorna los programas de gestion que tengan de responsable al usuario que cuenta como una
     */
    function findByUserAndPeriodNotGoals(User $user,Period $period,array $criteria = array())
    {
        $qb = $this->getQueryBuilder();
        $qb->innerJoin('ap.responsibles', 'ap_r')
           ->andWhere('ap_r.id = :responsible')
           ->andWhere('ap.period = :period')
           ->setParameter('responsible', $user)
           ->setParameter('period', $period);
        if(isset($criteria['notArrangementProgram'])){
            $qb->andWhere('ap.id != :arrangementProgram');
            $qb->setParameter('arrangementProgram', $criteria['notArrangementProgram']);
        }
        return $qb->getQuery()->getResult();
    }
    
    function findGroupByType($type,array $criteria = array())
    {
        $qb = $this->getQueryBuilder();
        $qb
            ->andWhere('ap.type = :type')
            ->setParameter('type',$type)
            ->andWhere('ap.status = :status')  
            ->setParameter('status', \Pequiven\ArrangementProgramBundle\Entity\ArrangementProgram::STATUS_REVISED)
            ;
        $qb
            ->innerJoin('ap.tacticalObjective','to')
            ->innerJoin('to.gerencia','to_g')
            ->innerJoin('to_g.configuration','to_g_c');
        if($type == \Pequiven\ArrangementProgramBundle\Entity\ArrangementProgram::TYPE_ARRANGEMENT_PROGRAM_TACTIC){
            $qb->innerJoin('to_g_c.arrangementProgramUsersToApproveTactical', 'to_g_c_apt')
               ->andWhere('to_g_c_apt.id = :user');
        }else if($type == \Pequiven\ArrangementProgramBundle\Entity\ArrangementProgram::TYPE_ARRANGEMENT_PROGRAM_OPERATIVE){
            $qb->innerJoin('to_g_c.arrangementProgramUsersToApproveOperative', 'to_g_c_ap')
               ->andWhere('to_g_c_ap.id = :user');
        }
        $qb->setParameter('user', $this->getUser());
        
        return $qb->getQuery()->getResult();
    }
    
    /**
     * 
     * @param array $criteria
     * @param array $orderBy
     * @return type
     */
    public function createPaginatorByRol(array $criteria = null, array $orderBy = null) {
        $this->getUser();
        return parent::createPaginator($criteria, $orderBy);
    }
    
    /**
     * Retorna los programas de gestion los cuales tengo asignados para revision o aprobacion
     * 
     * @param array $criteria
     * @param array $orderBy
     * @return type
     */
    public function createPaginatorByAssigned(array $criteria = null, array $orderBy = null) {
        $user = $this->getUser();
        
        $queryBuilder = $this->getCollectionQueryBuilder();
        
        
        $this->applyCriteria($queryBuilder, $criteria);
        $this->applySorting($queryBuilder, $orderBy);
        
        $queryBuilder
            ->innerJoin('to_g.configuration','to_g_c');
        
        $queryBuilder->leftJoin('to_g_c.arrangementProgramUserToRevisers', 'to_g_c_apr');
        $queryBuilder->leftJoin('to_g_c.arrangementProgramUsersToApproveTactical', 'to_g_c_apt');
        $queryBuilder->leftJoin('to_g_c.arrangementProgramUsersToApproveOperative', 'to_g_c_ap');
        
        $queryBuilder->andWhere($queryBuilder->expr()->orX('to_g_c_apr.id = :user','to_g_c_apt.id = :user','to_g_c_ap.id = :user'));
           
        $queryBuilder->setParameter('user', $user);
        return $this->getPaginator($queryBuilder);
    }
    
    /**
     * Retorna los programas de gestion los cuales tengo asignados para revision o aprobacion
     * 
     * @param array $criteria
     * @param array $orderBy
     * @return type
     */
    public function createPaginatorByAssignedResponsibles(array $criteria = null, array $orderBy = null) {
        
        $criteria = new \Doctrine\Common\Collections\ArrayCollection($criteria);
        
        $qb = $this->getQueryBuilder();
        $qb
            ->innerJoin('ap.responsibles', 'ap_r')
            ->innerJoin('ap.timeline', 't')
            ->innerJoin('t.goals', 't_g')
            ->innerJoin('t_g.responsibles', 't_g_r')
            ;
        
        if(($period = $criteria->remove('ap.period')) != null){
            $qb
                ->andWhere('ap.period = :period')
                ->setParameter('period', $period)
                ;
        }
        if(($user = $criteria->remove('ap.user')) != null){
            $qb
               ->andWhere($qb->expr()->orX('ap_r.id = :responsible','t_g_r.id = :responsible'))
               ->setParameter('responsible', $user)
                ;
        }
        
        $this->applyCriteria($qb,$criteria->toArray());
        $this->applySorting($qb, $orderBy);
        
        return $this->getPaginator($qb);
    }
    
    protected function applyCriteria(\Doctrine\ORM\QueryBuilder $queryBuilder, array $criteria = null) {
        $criteria = new \Doctrine\Common\Collections\ArrayCollection($criteria);
        
        $queryBuilder
                    ->leftJoin('ap.tacticalObjective', 'to')
                    ->leftJoin('to.gerencia', 'to_g')
                    ->leftJoin('ap.operationalObjective', 'oo')
                    ->leftJoin('oo.gerenciaSecond', 'gs')
                ;
        if(($ref = $criteria->remove('ap.ref'))){
            $queryBuilder->andWhere($queryBuilder->expr()->like('ap.ref',"'%".$ref."%'"));
        }
        if(($status = $criteria->remove('status')) !== null){
            $queryBuilder
                    ->andWhere('ap.status = :status')
                    ->setParameter('status', $status)
                    ;
        }
        if(($process = $criteria->remove('ap.process'))){
            $queryBuilder->andWhere($queryBuilder->expr()->like('ap.process',"'%".$process."%'"));
        }
        if(($period = $criteria->remove('ap.period'))){
            $queryBuilder->innerJoin('ap.period','p');
            $queryBuilder->andWhere($queryBuilder->expr()->like('p.name',"'%".$period."%'"));
        }
        
        if(($complejo = $criteria->remove('complejo')) != null && $complejo > 0){
            $queryBuilder
                    ->andWhere('to_g.complejo = :complejo')
                ->setParameter('complejo', $complejo)
                ;
        }
        
        if(($responsiblesJson = $criteria->remove('responsibles')) != null && $responsiblesJson != null){
            $responsibles = json_decode($responsiblesJson);
            if(is_array($responsibles)){
                $queryBuilder
                        ->innerJoin('ap.responsibles','ap_r')
                        ->andWhere($queryBuilder->expr()->in('ap_r.id', $responsibles))
                    ;
            }
        }
        if(($responsiblesGoalsJson = $criteria->remove('responsiblesGoals')) != null && $responsiblesGoalsJson != null){
            $responsiblesGoals = json_decode($responsiblesGoalsJson);
            if(is_array($responsiblesGoals)){
                $queryBuilder
                        ->innerJoin('ap.timeline','t')
                        ->innerJoin('t.goals','go')
                        ->innerJoin('go.responsibles','go_r')
                        ->andWhere($queryBuilder->expr()->in('go_r.id', $responsiblesGoals))
                    ;
            }
        }
        if(($tactical = $criteria->remove('tacticalObjective')) != null){
            $queryBuilder
                    ->andWhere('to.id = :tacticalObjective')
                ->setParameter('tacticalObjective', $tactical)
                ;
        }
        if(($operationalObjective = $criteria->remove('operationalObjective')) != null){
            $queryBuilder
                    ->andWhere('oo.id = :operationalObjective')
                ->setParameter('operationalObjective', $operationalObjective)
                ;
        }
        //Filtro de gerencia de primera linea
        if(($firstLineManagement = $criteria->remove('firstLineManagement')) != null){
            $queryBuilder
                    
                    ->andWhere('to_g.id = :firstLineManagement')
                ->setParameter('firstLineManagement', $firstLineManagement)
                ;
        }
        //Filtro de gerencia de segunda linea
        if(($secondLineManagement = $criteria->remove('secondLineManagement')) != null){
            $queryBuilder
                    ->andWhere('gs.id = :secondLineManagement')
                ->setParameter('secondLineManagement', $secondLineManagement)
                ;
        }
        //Filtro de gerencia de segunda linea modular y vinculante
        if(($typeManagement = $criteria->remove('typeManagement')) != null){
            if($typeManagement == \Pequiven\MasterBundle\Model\GerenciaSecond::TYPE_MANAGEMENT_MODULAR){
                $queryBuilder
                        ->andWhere('gs.modular = :typeManagement');
            }else{
                $queryBuilder
                        ->andWhere('gs.vinculante = :typeManagement');
            }
            $queryBuilder
                    ->setParameter('typeManagement', true)
                ;
        }
        
        parent::applyCriteria($queryBuilder, $criteria->toArray());
    }
    protected function applySorting(\Doctrine\ORM\QueryBuilder $queryBuilder, array $sorting = null) {
        parent::applySorting($queryBuilder, $sorting);
    }
    
    protected function getAlias() {
        return 'ap';
    }
}
