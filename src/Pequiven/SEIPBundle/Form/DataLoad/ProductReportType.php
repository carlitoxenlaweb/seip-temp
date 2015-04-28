<?php

namespace Pequiven\SEIPBundle\Form\DataLoad;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductReportType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('productionLine',null,array(
                'label_attr' => array('class' => 'label'),
                "attr" => array("class" => "select2 input-large"),
            ))
            ->add('product',"tecno_ajax_autocomplete",array(
                'label_attr' => array('class' => 'label'),
                //"empty_value" => "",
                "property" => array("name"),
                "entity_alias" => "products_alias"
            ))
            ->add('productUnit',"choice",array(
                'label_attr' => array('class' => 'label'),
                "attr" => array("class" => "select2 input-large"),
                "empty_value" => "",
                "choices" => \Pequiven\SEIPBundle\Model\DataLoad\ProductReport::getProductUnits(),
                "required" => true,
            ))
            ->add('plant',"tecno_ajax_autocomplete",array(
                'label_attr' => array('class' => 'label'),
                "property" => array("name"),
                "entity_alias" => "plants_alias",
            ))
            ->add('isRawMaterial',null,array(
                'label_attr' => array('class' => 'label'),
                "attr" => array("class" => "switch medium mid-margin-right","data-text-on"=>"Si","data-text-off"=>"No"),
                "required" => false,
            ))
            ->add('isFinalProduct',null,array(
                'label_attr' => array('class' => 'label'),
                "attr" => array("class" => "switch medium mid-margin-right","data-text-on"=>"Si","data-text-off"=>"No"),
                "required" => false,
            ))
            ->add('enabled',null,array(
                'label_attr' => array('class' => 'label'),
                "attr" => array("class" => "switch medium mid-margin-right","data-text-on"=>"Si","data-text-off"=>"No"),
                "required" => false,
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pequiven\SEIPBundle\Entity\DataLoad\ProductReport',
            "translation_domain" => "PequivenSEIPBundle",
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pequiven_seipbundle_dataload_productreport';
    }
}
