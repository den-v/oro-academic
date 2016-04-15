<?php

namespace OroAcademic\Bundle\IssueBundle\ImportExport\TemplateFixture;

use Oro\Bundle\ImportExportBundle\TemplateFixture\AbstractTemplateRepository;
use Oro\Bundle\ImportExportBundle\TemplateFixture\TemplateFixtureInterface;
use OroAcademic\Bundle\IssueBundle\Entity\Issue;
use OroAcademic\Bundle\IssueBundle\Entity\IssuePriority;

class IssueFixture extends AbstractTemplateRepository implements TemplateFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function getEntityClass()
    {
        return 'OroAcademic\Bundle\IssueBundle\Entity\Issue';
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->getEntityData('BB-1234');
    }

    /**
     * {@inheritdoc}
     */
    protected function createEntity($key)
    {
        return new Issue();
    }

    /**
     * @param string $key
     * @param Issue   $entity
     */
    public function fillEntityData($key, $entity)
    {
        $userRepo         = $this->templateManager
            ->getEntityRepository('Oro\Bundle\UserBundle\Entity\User');
        $organizationRepo = $this->templateManager
            ->getEntityRepository('Oro\Bundle\OrganizationBundle\Entity\Organization');

        switch ($key) {
            case 'BB-1234':
                $entity->setCode('BB-1234');
                $entity->setSummary('Template Task');
                $entity->setDescription('Template Task Description');
                $entity->setType('Task');
                $entity->setPriority(new IssuePriority('low'));
                $entity->setReporter($userRepo->getEntity('John Doo'));
                $entity->setAssignee($userRepo->getEntity('John Doo'));
                $entity->setOrganization($organizationRepo->getEntity('default'));
                $entity->setCreatedAt(new \DateTime());
                $entity->setUpdatedAt(new \DateTime());

                return;
        }

        parent::fillEntityData($key, $entity);
    }
}
