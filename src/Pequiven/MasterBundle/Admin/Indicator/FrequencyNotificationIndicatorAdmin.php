<?php

namespace Pequiven\MasterBundle\Admin\Indicator;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Pequiven\MasterBundle\Model\MasterAdminInterface;

/**
 * Administrador de las frecuencias de notificacion de los indicadores
 *
 * @author Carlos Mendoza<inhack20@gmail.com>
 */
class FrequencyNotificationIndicatorAdmin extends Admin implements ContainerAwareInterface, MasterAdminInterface
{
    private $container;
    
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
            ->add('textAbbr')
            ->add('days')
            ->add('enabled')
                ;
    }
    
    protected function configureFormFields(FormMapper $form) {
        $form
            ->add('description')
            ->add('textAbbr')
            ->add('days')
            ->add('enabled')
                ;
    }
    
    protected function configureDatagridFilters(DatagridMapper $filter) {
        $filter
            ->add('description')
            ->add('days')
            ->add('enabled')
        ;
    }
    
    protected function configureListFields(ListMapper $list) {
        $list
            ->addIdentifier('description')
            ->add('textAbbr')
            ->add('days')
            ->add('enabled')
            ;
    }
    
    public function prePersist($object) 
    {
        $object->setPeriod($this->getPeriodService()->getPeriodActive());
    }

    public function setContainer(\Symfony\Component\DependencyInjection\ContainerInterface $container = null) 
    {
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
