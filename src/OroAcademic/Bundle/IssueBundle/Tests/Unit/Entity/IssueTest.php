<?php
/**
 * Created by PhpStorm.
 * User: vdenchyk
 * Date: 08.04.16
 * Time: 16:15
 */

namespace OroAcademic\Bundle\IssueBundle\Tests\Unit\Entity;

use OroAcademic\Bundle\IssueBundle\Entity\Issue;

class IssueTest extends \PHPUnit_Framework_TestCase
{
    protected $organization;

    protected $assignee;

    protected $reporter;

    public function setUp()
    {
        parent::setUp();
        $this->organization = $this->getMock('Oro\Bundle\OrganizationBundle\Entity\Organization');
        $this->assignee = $this->getMock('Oro\Bundle\UserBundle\Entity\User');
        $this->reporter = $this->getMock('Oro\Bundle\UserBundle\Entity\User');

    }

    public function testCreate()
    {
        new Issue();
    }

    /**
     * @dataProvider settersAndGettersDataProvider
     */
    public function testSettersAndGetters($property, $value)
    {
        $obj = new Issue();

        call_user_func_array(array($obj, 'set' . ucfirst($property)), array($value));
        $this->assertEquals($value, call_user_func_array(array($obj, 'get' . ucfirst($property)), array()));
    }

    public function settersAndGettersDataProvider()
    {
        $priority = $this->getMockBuilder('OroCRM\Bundle\TaskBundle\Entity\TaskPriority')
            ->disableOriginalConstructor()
            ->getMock();
        $priority->expects($this->once())->method('getName')->will($this->returnValue('low'));
        $priority->expects($this->once())->method('getLabel')->will($this->returnValue('Low label'));

        return array(
            array('code', 'BB-1234'),
            array('summary', 'Test subject'),
            array('type', 'Bug'),
            array('description', 'Test Description'),
            array('priority', $priority),
            array('createdAt', new \DateTime()),
            array('updatedAt', new \DateTime()),
            array('organization', $this->organization),
            array('reporter', $this->reporter),
            array('assignee', $this->assignee)
        );
    }

    public function testToString()
    {
        $entity = new Issue();
        $this->assertEmpty((string)$entity);
        $entity->setCode('BB-1234');
        $this->assertEmpty((string)$entity);
        $entity->setSummary('Summary');
        $this->assertEquals('BB-1234: Summary', (string)$entity);
    }

    public function tearDown()
    {

        parent::tearDown();
        unset($this->organization);
        unset($this->assignee);
        unset($this->reporter);
    }
}
