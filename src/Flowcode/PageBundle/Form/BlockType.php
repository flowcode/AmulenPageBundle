<?php

namespace Flowcode\PageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BlockType extends AbstractType {

    protected $availableTypes;

    /**
    * @param FormBuilderInterface $builder
    * @param array $options
    */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
        ->add('name')
        ->add('content')
        ->add('page')
        ->add('type', 'hidden')
        ->add('lang', 'choice', array(
            'choices'  => array('es' => 'es', 'en' => 'en'),
            'required' => false,
        ))
        ;
    }

    /**
    * @param OptionsResolverInterface $resolver
    */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Amulen\PageBundle\Entity\Block'
        ));
    }

    /**
    * @return string
    */
    public function getName() {
        return 'flowcode_pagebundle_block';
    }

}
