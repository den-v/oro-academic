<?php

namespace OroAcademic\Bundle\IssueBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class IssueApiType extends AbstractIssueType
{
    const NAME = 'issue';

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
}
