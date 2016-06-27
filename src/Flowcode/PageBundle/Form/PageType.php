<?php

namespace Flowcode\PageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PageType extends AbstractType {

    protected $availableTemplates;

    function __construct($availableTemplates = null) {
        $this->availableTemplates = $availableTemplates;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name')
                ->add('description')
                ->add('image')
                ->add('template', 'choice', array(
                    'choices' => $this->availableTemplates,
                    'required' => false,
                ))
                ->add('category', 'y_tree', array(
                       'class' => "Amulen\ClassificationBundle\Entity\Category",
                       'orderFields' => array('root' => 'asc','lft' => 'asc'),
                       'prefixAttributeName' => 'data-level-prefix',
                       'treeLevelField' => 'lvl',
                       'required' => false,
                       'multiple' => false,
                       'attr' => array("class" => "tall")))
                ->add('position')
                ->add('enabled', null, array('required' => false,))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Amulen\PageBundle\Entity\Page'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'flowcode_pagebundle_page';
    }

}
