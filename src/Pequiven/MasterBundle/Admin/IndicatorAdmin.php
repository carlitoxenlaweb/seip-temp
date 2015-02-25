<?php

namespace Pequiven\MasterBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Administrador del Indicador
 *
 * @author Carlos Mendoza<inhack20@gmail.com>
 */
class IndicatorAdmin extends Admin implements \Symfony\Component\DependencyInjection\ContainerAwareInterface
{   
    private $container;
    
    protected function configureShowFields(\Sonata\AdminBundle\Show\ShowMapper $show) {
        $show
            ->add('ref')
            ->add('description')
            ->add('typeOfCalculation','choice',array(
                'choices' => \Pequiven\IndicatorBundle\Entity\Indicator::getTypesOfCalculation(),
                'translation_domain' => 'PequivenIndicatorBundle'
            ))
            ->add('refParent')
            ->add('totalPlan')
            ->add('weight')
            ->add('goal')
            ->add('formula')
            ->add('tendency')
            ->add('arrangementRange')
            ->add('frequencyNotificationIndicator')
            ->add('valueFinal')
            ->add('childrens')
            ->add('valuesIndicator')
            ->add('couldBePenalized')
            ->add('forcePenalize')
            ->add('requiredToImport')
            ->add('details')
            ;
    }
    
    protected function configureFormFields(FormMapper $form) 
    {
        $object = $this->getSubject();
        $childrensParameters = array(
            'class' => 'Pequiven\IndicatorBundle\Entity\Indicator',
            'multiple' => true,
            'required' => false,
        );
        $id = null;
        if($object != null && $object->getId() !== null){
            $indicatorLevel = $object->getIndicatorLevel();
            $level = $indicatorLevel->getLevel();
            $childrensParameters['query_builder'] = function(\Pequiven\IndicatorBundle\Repository\IndicatorRepository $repository) use ($level){
                return $repository->getQueryChildrenLevel($level);
            };
            $id = $object->getId();
        }
        
        $form
            ->add('ref')
            ->add('description')
            ->add('typeOfCalculation','choice',array(
                'choices' => \Pequiven\IndicatorBundle\Entity\Indicator::getTypesOfCalculation(),
                'translation_domain' => 'PequivenIndicatorBundle'
            ))
            ->add('refParent')
            ->add('totalPlan')
            ->add('weight')
            ->add('goal')
            ->add('formula')
            ->add('tendency')
            ->add('frequencyNotificationIndicator')
            ->add('valueFinal')
            ->add('childrens','entity',$childrensParameters)
            ->add('formulaDetails','sonata_type_collection',
                array(
                     'cascade_validation' => true,
                     'by_reference' => false,
//                    'type_options' => array(
//                        'delete' => true,
//                    ),
                ),
                array(
                    'edit'   => 'inline',
                    'inline' => 'table',
//                    'sortable' => 'position',
                    'link_parameters' => array('indicator_id' => $id)
                ),
                array(
                )
            )    
            ->add('couldBePenalized',null,array(
                'required' => false,
            ))
            ->add('forcePenalize',null,array(
                'required' => false,
            ))
            ->add('requiredToImport',null,array(
                'required' => false,
            ))
            ->add('enabled',null,array(
                'required' => false,
            ))->end();
        $form
            ->with('Details')
                ->add('details','sonata_type_admin',array(
                     'cascade_validation' => true,
                     'delete' => false,
                ),
                array(
                    'edit'   => 'inline',
                    'inline' => 'table',
                )
                )
            ->end()
            ;
    }
    
    protected function configureDatagridFilters(DatagridMapper $filter) {
        $filter
            ->add('ref')
            ->add('description')
            ->add('formula')
            ->add('typeOfCalculation',null,array(),'choice',array(
                'choices' => \Pequiven\IndicatorBundle\Entity\Indicator::getTypesOfCalculation(),
                'translation_domain' => 'PequivenIndicatorBundle'
            ))
            ->add('tendency')
            ->add('frequencyNotificationIndicator')
            ->add('valueFinal')
            ->add('couldBePenalized')
            ->add('forcePenalize')
            ->add('requiredToImport')
            ->add('enabled')
            ;
    }
    
    protected function configureListFields(ListMapper $list) {
        $list
            ->addIdentifier('ref')
            ->add('description')
            ->add('formula')
            ->add('frequencyNotificationIndicator')
            ->add('valueFinal')
            ->add('tendency')
            ->add('arrangementRange')
            ->add('enabled')
            ;
    }
    
    public function prePersist($object) 
    {
        
        $object->setPeriod($this->getPeriodService()->getPeriodActive());
        if($object->isCouldBePenalized() === false){
            $object->setForcePenalize(false);
        }
        foreach ($object->getFormulaDetails() as $formulaDetails)
        {
            $formulaDetails->setIndicator($object);
        }
    }
    
    public function preUpdate($object) 
    {
        foreach ($object->getFormulaDetails() as $formulaDetails)
        {
            $formulaDetails->setIndicator($object);
        }
        if($object->isCouldBePenalized() === false){
            $object->setForcePenalize(false);
        }
    }
    
    /**
     * Servicio que calcula los resultados
     * @return \Pequiven\SEIPBundle\Service\ResultService
     */
    public function getResultService()
    {
        return $this->container->get('seip.service.result');
    }
    
    /**
     * @return \Pequiven\SEIPBundle\Service\PeriodService
     */
    private function getPeriodService()
    {
        return $this->container->get('pequiven_arrangement_program.service.period');
    }
    
    public function setContainer(\Symfony\Component\DependencyInjection\ContainerInterface $container = null) {
        $this->container = $container;
    }
    
    protected function configureRoutes(\Sonata\AdminBundle\Route\RouteCollection $collection) 
    {
        $collection->remove('create');
    }
}
