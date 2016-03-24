<?php

namespace OroAcademic\Bundle\IssueBundle\Form\Type;

use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;

use Oro\Bundle\FormBundle\Utils\FormUtils;
use OroAcademic\Bundle\IssueBundle\Entity\Issue;

class IssueType extends AbstractType
{

    private $issueTypes;

    public function __construct(Array $issueTypes)
    {
        $this->issueTypes = $issueTypes;
    }
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'code',
                'text',
                [
                    'required' => true,
                    'label' => 'oroacademic.issue.code.label'
                ]
            )
            ->add(
                'summary',
                'text',
                [
                    'required' => true,
                    'label' => 'oroacademic.issue.summary.label'
                ]
            )
            ->add(
                'type',
                'choice',
                [
                    'multiple' => false,
                    'label' => 'oroacademic.issue.type.label',
                    'choices' => $this->issueTypes
                ]
            )
            ->add(
                'description',
                'textarea',
                [
                    'required' => false,
                    'label' => 'oroacademic.issue.description.label'
                ]
            )
            ->add(
                'priority',
                'translatable_entity',
                [
                    'label' => 'oroacademic.issue.priority.label',
                    'class' => 'OroAcademic\Bundle\IssueBundle\Entity\IssuePriority',
                    'required' => true,
                    'query_builder' => function (EntityRepository $repository) {
                        return $repository->createQueryBuilder('priority')->orderBy('priority.order');
                    }
                ]
            )
            ->add(
                'assignee',
                'oro_user_select',
                [
                    'label' => 'oroacademic.issue.assignee.label',
                    'required' => true
                ]
            )
            ->add(
                'relatedIssues',
                'translatable_entity',
                [
                    'label' => 'oroacademic.issue.related.label',
                    'class' => 'OroAcademic\Bundle\IssueBundle\Entity\Issue',
                    'multiple' => true,
                    'required' => false
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'OroAcademic\Bundle\IssueBundle\Entity\Issue',
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'oroacademic_issue';
    }
}
