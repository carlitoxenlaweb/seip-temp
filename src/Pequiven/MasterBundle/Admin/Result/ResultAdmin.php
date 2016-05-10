<?php

namespace Pequiven\MasterBundle\Admin\Result;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Pequiven\MasterBundle\Model\MasterAdminInterface;
/**
 * Administrador de resultado
 *
 * @author Carlos Mendoza<inhack20@gmail.com>
 */
class ResultAdmin extends Admin implements ContainerAwareInterface, MasterAdminInterface
{
    protected $container;
    
    protected $modelManager;

    public function setModelManager(\Sonata\AdminBundle\Model\ModelManagerInterface $modelManager) {
        parent::setModelManager($modelManager);
        $this->modelManager = $modelManager;
    }

    public function setCustomEntityManager(\Pequiven\MasterBundle\Service\MasterConnection $connection) {
        $this->modelManager->setEntityManagerName($connection->getManagerName());
    }
    
    protected function configureShowFields(\Sonata\AdminBundle\Show\ShowMapper $show) 
    {
        $show
            ->add('description')
            ->add('weight')
            ->add('typeResult')
            ->add('typeCalculation','choice',array(
                'choices' => \Pequiven\SEIPBundle\Model\Result\Result::getTypeCalculations(),
                'translation_domain' => 'PequivenSEIPBundle'
            ))
            ->add('objetive')
            ->add('parent')
            ;
    }
    
    protected function configureFormFields(FormMapper $form) {
        $form
            ->add('description')
            ->add('weight')
            ->add('typeResult','choice',array(
                'choices' => \Pequiven\SEIPBundle\Model\Result\Result::getTypeResults(),
                'translation_domain' => 'PequivenSEIPBundle'
            ))
            ->add('typeCalculation','choice',array(
                'choices' => \Pequiven\SEIPBundle\Model\Result\Result::getTypeCalculations(),
                'translation_domain' => 'PequivenSEIPBundle'
            ))
            ->add('objetive', null, array(
                'em' => $this->modelManager->getEntityManagerName()
            ))
            ->add('parent','entity',array(
                'class' => 'Pequiven\SEIPBundle\Entity\Result\Result',
                'query_builder' => function(\Pequiven\SEIPBundle\Repository\Result\ResultRepository $repository){
                    return $repository->getQueryOfValidParents();
                },
                'required' => false,
                'em' => $this->modelManager->getEntityManagerName()
            ))
        ;
    }
    
    protected function configureDatagridFilters(DatagridMapper $filter) {
        $filter
            ->add('description')
            ->add('typeResult')
            ->add('typeCalculation')
            ->add('objetive')
            ->add('period')
            ;
    }
    
    protected function configureListFields(ListMapper $list) {
        $list
            ->addIdentifier('description')
            ->add('typeResult')
            ->add('typeCalculation')
            ->add('objetive')
            ;
    }
    
    public function prePersist($object) 
    {
        $object->setPeriod($this->getPeriodService()->getPeriodActive());
        $parent = $object->getParent();
        if($parent != null){
            $object->setObjetive(null);
        }
    }
    
    public function preUpdate($object) {
        $parent = $object->getParent();
        if($parent != null){
            $object->setObjetive(null);
        }
    }

    public function setContainer(\Symfony\Component\DependencyInjection\ContainerInterface $container = null) {
        $this->container = $container;
    }
    
    /**
     * @return \Pequiven\SEIPBundle\Service\PeriodService
     */
    protected function getPeriodService()
    {
        return $this->container->get('pequiven_seip.service.period');
    }
}
