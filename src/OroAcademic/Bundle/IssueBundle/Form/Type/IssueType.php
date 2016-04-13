<?php

namespace OroAcademic\Bundle\IssueBundle\Form\Type;

use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;

use OroAcademic\Bundle\IssueBundle\Entity\Issue;

class IssueType extends AbstractType
{

    private $issueTypes;

    public function __construct(array $issueTypes)
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
                    'required' => true
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
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $issue = $event->getData();
            $form = $event->getForm();

            // check if Issue object is not "new"
            if ($issue && null !== $issue->getId()) {
                $form->remove('relatedIssues');
                $form
                    ->add(
                        'relatedIssues',
                        'translatable_entity',
                        [
                        'label' => 'oroacademic.issue.related.label',
                        'class' => 'OroAcademic\Bundle\IssueBundle\Entity\Issue',
                        'multiple' => true,
                        'required' => false,
                        'query_builder' => function (EntityRepository $repository) use ($issue) {
                            return $repository->createQueryBuilder('issue')
                                ->andWhere('issue.id != :id')
                                ->setParameter(':id', $issue->getId());
                        }
                        ]
                    );
            }
        });
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
