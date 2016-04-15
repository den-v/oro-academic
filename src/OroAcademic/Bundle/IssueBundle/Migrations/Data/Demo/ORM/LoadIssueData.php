<?php

namespace OroAcademic\Bundle\IssueBundle\Migrations\Data\Demo\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\UserBundle\Entity\User;
use OroAcademic\Bundle\IssueBundle\Entity\Issue;
use OroAcademic\Bundle\IssueBundle\Entity\IssuePriority;
use Oro\Bundle\NoteBundle\Entity\Note;
use Doctrine\ORM\EntityManager;

class LoadIssueData extends AbstractFixture implements DependentFixtureInterface, ContainerAwareInterface
{
    /**
     * @var array
     */
    protected $data = array(
        array(
            'code' => 'BB-1111',
            'summary' => 'First Bug',
            'type' => 'Bug',
            'notes' => 2,
            'related' => 0
        ),
        array(
            'code' => 'BB-1112',
            'summary' => 'Second Bug',
            'type' => 'Bug',
            'notes' => 3,
            'related' => 1
        ),
        array(
            'code' => 'BB-1113',
            'summary' => 'Third Bug',
            'type' => 'Bug',
            'notes' => 2,
            'related' => 2
        ),
        array(
            'code' => 'BB-2111',
            'summary' => 'First Task',
            'type' => 'Task',
            'notes' => 2,
            'related' => 2
        ),
        array(
            'code' => 'BB-2112',
            'summary' => 'Second Task',
            'type' => 'Task',
            'notes' => 3,
            'related' => 3
        ),
        array(
            'code' => 'BB-2113',
            'summary' => 'Third Task',
            'type' => 'Task',
            'notes' => 2,
            'related' => 3
        ),
        array(
            'code' => 'BB-3111',
            'summary' => 'First Story',
            'type' => 'Story',
            'notes' => 2,
            'related' => 2
        ),
        array(
            'code' => 'BB-3121',
            'summary' => 'Second Story',
            'type' => 'Story',
            'notes' => 3,
            'related' => 3
        ),
        array(
            'code' => 'BB-3131',
            'summary' => 'Third Story',
            'type' => 'Story',
            'notes' => 2,
            'related' => 3
        ),
        array(
            'code' => 'BB-3112',
            'summary' => 'First Subtask(BB-3111)',
            'type' => 'Subtask',
            'notes' => 2,
            'related' => 2,
            'parent' => 'BB-3111'
        ),
        array(
            'code' => 'BB-3113',
            'summary' => 'Second Subtask(BB-3111)',
            'type' => 'Subtask',
            'notes' => 3,
            'related' => 3,
            'parent' => 'BB-3111'
        ),
        array(
            'code' => 'BB-3114',
            'summary' => 'Third Subtask(BB-3111)',
            'type' => 'Subtask',
            'notes' => 3,
            'related' => 3,
            'parent' => 'BB-3111'
        ),
        array(
            'code' => 'BB-3122',
            'summary' => 'First Subtask(BB-3121)',
            'type' => 'Subtask',
            'notes' => 2,
            'related' => 2,
            'parent' => 'BB-3121'
        ),
        array(
            'code' => 'BB-3123',
            'summary' => 'Second Subtask(BB-3121)',
            'type' => 'Subtask',
            'notes' => 3,
            'related' => 3,
            'parent' => 'BB-3121'
        ),
        array(
            'code' => 'BB-3124',
            'summary' => 'Third Subtask(BB-3121)',
            'type' => 'Subtask',
            'notes' => 3,
            'related' => 3,
            'parent' => 'BB-3121'
        ),
        array(
            'code' => 'BB-3132',
            'summary' => 'First Subtask(BB-3131)',
            'type' => 'Subtask',
            'notes' => 2,
            'related' => 2,
            'parent' => 'BB-3131'
        ),
        array(
            'code' => 'BB-3133',
            'summary' => 'Second Subtask(BB-3131)',
            'type' => 'Subtask',
            'notes' => 3,
            'related' => 3,
            'parent' => 'BB-3131'
        ),
        array(
            'code' => 'BB-3134',
            'summary' => 'Third Subtask(BB-3131)',
            'type' => 'Subtask',
            'notes' => 3,
            'related' => 3,
            'parent' => 'BB-3131'
        ),
    );

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var  EntityManager
     */
    protected $em;

    /**
     * @var Organization
     */
    protected $organization;

    /**
     * @var array
     */
    protected $users;

    /**
     * @var array
     */
    protected $priorities;

    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return [
            'OroAcademic\Bundle\IssueBundle\Migrations\Data\Demo\ORM\LoadUsersData',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param ObjectManager $manager
     */
    protected function initSupportingEntities(ObjectManager $manager = null)
    {
        if ($manager) {
            $this->em = $manager;
        }
        $this->organization = $manager->getRepository('OroOrganizationBundle:Organization')->getFirst();
        $this->users = $manager->getRepository('OroUserBundle:User')->findAll();
        $this->priorities = $manager->getRepository('OroAcademicIssueBundle:IssuePriority')->findAll();
    }
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->initSupportingEntities($manager);
        $this->loadIssues();
    }

    /**
     * Load users
     *
     * @return void
     */
    public function loadIssues()
    {
        /**
         * @var User $randomAssignee
         * @var User $randomReporter
         * @var Issue $randomIssue
         * @var IssuePriority $randomPriority
         */
        foreach ($this->data as $data) {
            $existIssues = $this->em->getRepository('OroAcademicIssueBundle:Issue')->findAll();
            $randomAssignee = $this->getRandomEntity($this->users);
            $randomReporter = $this->getRandomEntity($this->users);
            $randomPriority = $this->getRandomEntity($this->priorities);

            $issue = new Issue();
            
            $issue->setSummary($data['summary']);
            $issue->setCode($data['code']);
            $issue->setDescription($data['summary']." Description");
            $issue->setAssignee($randomAssignee);
            $issue->setReporter($randomReporter);
            $issue->setType($data['type']);
            $issue->setPriority($randomPriority);
            $issue->setOrganization($this->organization);
            if ($data['type'] == 'Subtask' && $data['parent']) {
                $parent = $this->em
                    ->getRepository('OroAcademicIssueBundle:Issue')
                    ->findOneBy(['code' => $data['parent']]);
                $issue->setParent($parent);

            }
            for ($i = 1; $i <= $data['related']; $i++) {
                if ($existIssues) {
                    $randomIssue = $this->getRandomEntity($existIssues);
                    $issue->addRelatedIssue($randomIssue);
                }
            }
            $this->persist($issue);
            for ($i = 1; $i <= $data['notes']; $i++) {
                $note = new Note();
                $note->setMessage('Note #'.$i)
                    ->setOrganization($this->organization)
                    ->setOwner($this->getRandomEntity($this->users))
                    ->setTarget($issue);
                $this->persist($note);
            }
            $this->flush();
        }
    }

    /**
     * @param object[] $entities
     *
     * @return object|null
     */
    protected function getRandomEntity($entities)
    {
        if (empty($entities)) {
            return null;
        }

        return $entities[rand(0, count($entities) - 1)];
    }

    /**
     * Persist object
     *
     * @param  mixed $object
     * @return void
     */
    private function persist($object)
    {
        $this->em->persist($object);
    }

    /**
     * Flush objects
     *
     * @return void
     */
    private function flush()
    {
        $this->em->flush();
    }
}
