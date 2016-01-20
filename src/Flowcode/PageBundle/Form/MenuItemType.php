<?php

namespace Flowcode\PageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MenuItemType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name')
                ->add('parent', 'y_tree', array(
                       'class' => "Amulen\PageBundle\Entity\MenuItem",
                       'orderFields' => array('root' => 'asc','lft' => 'asc'),
                       'prefixAttributeName' => 'data-level-prefix',
                       'treeLevelField' => 'lvl',
                       'required' => false,
                       'multiple' => false,
                       'attr' => array("class" => "tall")))
                ->add('page')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Amulen\PageBundle\Entity\MenuItem'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'flowcode_pagebundle_menuitem';
    }

}
