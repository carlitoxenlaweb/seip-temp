<?php

namespace Pequiven\IndicatorBundle\Form\Indicator\ValueIndicator;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Pequiven\IndicatorBundle\Entity\Indicator;

class ValueIndicatorConfigType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //Se comento por error en la busqueda de ajax no esta seteado el indicador
//        $data = $builder->getData();
//        $typeDetailValue = $data->getIndicator()->getTypeDetailValue();
//        if($typeDetailValue == Indicator::TYPE_DETAIL_DAILY_LOAD_PRODUCTION){
            $builder
                ->add('products','tecno_ajax_autocomplete',array(
                    'label' => 'form.products',
                    'entity_alias' => 'products_alias',
                    'label_attr' => array('class' => 'label'),
                    'attr' => array(
                        "class" => "input input-xlarge validate[required]"
                    ),
                    "property" => array("name","id"),
                    "multiple" => true,
                    "callback" => function (\Pequiven\SEIPBundle\Repository\CEI\ProductRepository $qb)
                    {
                        return $qb->getQueryAllComponents();
                    }
                ))
            ;
//        }
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pequiven\IndicatorBundle\Entity\Indicator\ValueIndicator\ValueIndicatorConfig',
            'translation_domain' => 'PequivenIndicatorBundle',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pequiven_indicatorbundle_indicator_valueindicator_valueindicatorconfig';
    }
}
