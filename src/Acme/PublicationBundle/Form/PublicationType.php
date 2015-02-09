<?php

namespace Acme\PublicationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PublicationType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', ['required' => false])
            ->add('category', 'entity', [
                'class' => 'Acme\PublicationBundle\Entity\Category',
                'property' => 'name'
                ]
            )
            ->add('content', 'textarea', ['required' => false])
            ->add('tags', 'entity', [
                'class' => 'Acme\PublicationBundle\Entity\Tag',
                'property' => 'name',
                'multiple' => true,
                'expanded' => true
                ]
            )
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Acme\PublicationBundle\Entity\Publication'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'publication';
    }
}
