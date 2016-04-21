<?php

namespace OroAcademic\Bundle\IssueBundle\Tests\Functional\Entity\Repository;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

/**
 * @dbIsolation
 */
class IssueRepositoryTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->initClient([], $this->generateBasicAuthHeader());
        $this->loadFixtures(
            [
                'OroAcademic\Bundle\IssueBundle\Migrations\Data\Demo\ORM\LoadIssueData',
            ]
        );
        $this->em = $this->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testGetIssuesByStatus()
    {
        $aclHelper = $this->getContainer()->get('oro_security.acl_helper');

        $statuses = $this->em
            ->getRepository('OroAcademicIssueBundle:Issue')->getIssuesByStatus($aclHelper, null, null);

        $this->assertEquals(5, count($statuses));

        foreach ($statuses as $status) {
            $this->assertArrayHasKey('name', $status);
            $this->assertArrayHasKey('label', $status);
            $this->assertArrayHasKey('issues', $status);
            if ($status['name'] == 'open') {
                $cnt = 18;
            } else {
                $cnt = 0;
            }
            $this->assertEquals($cnt, $status['issues']);
        }
    }
}
