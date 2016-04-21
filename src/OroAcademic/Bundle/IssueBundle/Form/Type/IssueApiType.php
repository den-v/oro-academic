<?php

namespace OroAcademic\Bundle\IssueBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Oro\Bundle\SoapBundle\Form\EventListener\PatchSubscriber;

class IssueApiType extends IssueType
{
    const NAME = 'issue';
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'OroAcademic\Bundle\IssueBundle\Entity\Issue',
                'csrf_protection' => false
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'issue';
    }
}
