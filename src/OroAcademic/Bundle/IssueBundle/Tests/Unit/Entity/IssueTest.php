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
    public function testCreate()
    {
        new Issue();
    }

    public function testSetReporter()
    {
        $entity = new Issue();

        $this->assertNull($entity->getReporter());

        $user = $this->getMock('Oro\Bundle\UserBundle\Entity\User');
        $entity->setReporter($user);

        $this->assertEquals($user, $entity->getReporter());
    }
}
