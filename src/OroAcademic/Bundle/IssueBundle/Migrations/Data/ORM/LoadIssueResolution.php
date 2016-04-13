<?php

namespace OroAcademic\Bundle\IssueBundle\Migrations\Data\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

use OroAcademic\Bundle\IssueBundle\Entity\IssueResolution;

class LoadIssueResolution extends AbstractFixture
{
    /**
     * @var array
     */
    protected $data = array(
        array(
            'label' => 'Cannot Reproduce',
            'name' => 'cnt_reproduce',
        ),
        array(
            'label' => 'Duplicate',
            'name' => 'duplicate',
        ),
        array(
            'label' => 'Fixed',
            'name' => 'fixed',
        ),
        array(
            'label' => 'Incomplete',
            'name' => 'incomplete',
        ),
        array(
            'label' => 'Invalid',
            'name' => 'invalid',
        ),
        array(
            'label' => 'Redundant',
            'name' => 'redundant',
        ),
        array(
            'label' => 'Revisit in Future',
            'name' => 'revisit_in_ft',
        ),
        array(
            'label' => 'Works as designed',
            'name' => 'work_as_design',
        ),
        array(
            'label' => 'Won\'t Fix',
            'name' => 'wt_fix',
        )
    );

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->data as $resolution) {
            if (!$this->isResolutionExist($manager, $resolution['name'])) {
                $entity = new IssueResolution($resolution['name']);
                $entity->setLabel($resolution['label']);
                $manager->persist($entity);
            }
        }

        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     * @param string $priorityType
     * @return bool
     */
    private function isResolutionExist(ObjectManager $manager, $resolutionName)
    {
        return count($manager->getRepository('OroAcademicIssueBundle:IssueResolution')->find($resolutionName)) > 0;
    }
}
