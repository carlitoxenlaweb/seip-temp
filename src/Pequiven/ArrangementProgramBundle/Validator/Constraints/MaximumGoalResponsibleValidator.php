<?php

namespace Pequiven\ArrangementProgramBundle\Validator\Constraints;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Valida que una persona no pueda tener mas de "n" metas activas asignadas en un periodo
 *
 * @author Carlos Mendoza <inhak20@tecnocreaciones.com>
 */
class MaximumGoalResponsibleValidator extends ConstraintValidator implements ContainerAwareInterface
{
    /**
     * Contenedor de dependencias
     * @var ContainerInterface
     */
    private $container;
    
    public function validate($object, Constraint $constraint)
    {
        $timeline = $object->getTimeline();
        //Sino se asigno ninguna meta al crear el programa de gestion
        if(!$timeline){
            return;
        }
        $limitGoals = 12;
        $period = $this->container->get('pequiven.repository.period')->findOneActive();
        
        //Repositorios
        $goalRepository = $this->container->get('pequiven_seip.repository.arrangementprogram_goal');
        $arrangementProgramRepository = $this->container->get('pequiven_seip.repository.arrangementprogram');
        
        $errors = array();
        $criteria = array();
        if($object->getId() > 0){
            $criteria['notArrangementProgram'] = $object;
        }
        foreach ($timeline->getGoals() as $currentGoal) {
            $reposibles = $currentGoal->getResponsibles();
            foreach ($reposibles as $reposible) {
                //Metas de otros programa de gestion donde no es reponsable
                $goals = $goalRepository->findGoalsByUserAndPeriod($reposible,$period,$criteria);
                //Programas de gestion donde es responsable
                $arrangementPrograms = $arrangementProgramRepository->findByUserAndPeriodNotGoals($reposible,$period,$criteria);

                $countGoals = count($goals);
                $countArrangementPrograms = count($arrangementPrograms);

                    $isReposibleInProgram = false;
                    foreach ($object->getResponsibles() as $reposibleProgram) {
                        //Se busca a ver si es uno de los responsables del programa
                        if($reposibleProgram === $reposible){
                            $isReposibleInProgram = true;
                            break;
                        }
                    }
                    //Se suma la responsabilidad del programa actual y no se evaluan las metas
                    if($isReposibleInProgram === true){
                        $countArrangementPrograms++;
                    }else{
                        $countGoals++;//Mas 1 de la meta actual
                        //Se evaluan las metas xq no es responsable del programa de gestion
                        foreach ($timeline->getGoals() as $subGoal) {
                            if($currentGoal !== $subGoal){
                                foreach ($subGoal->getResponsibles() as $subReposible) {
                                    if($subReposible === $reposible){
                                        $countGoals++;//Mas 1 de otras metas del mismo programa de gestion
                                    }
                                }
                            }
                        }
                    }

                $total = $countGoals + $countArrangementPrograms;
                if($total > $limitGoals){
                    $errors[$reposible->getId()] = array(
                        '%user%' => $reposible,
                        '%goals%' => $countGoals,
                        '%limit%'=> $limitGoals,
                        '%period%' => $period,
                        '%arrangementPrograms%' => $countArrangementPrograms,
                        '%total%' => ($countArrangementPrograms + $countGoals)
                    );
                }
            }
        }
        
        foreach ($errors as $error) {
            $this->context->addViolation($constraint->message,$error);
        }
    }
    
    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }
}
