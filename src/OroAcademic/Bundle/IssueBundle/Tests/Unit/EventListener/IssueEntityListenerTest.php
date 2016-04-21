<?php

namespace OroAcademic\Bundle\IssueBundle\Tests\Unit\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Oro\Bundle\NavigationBundle\Content\TopicSender;
use OroAcademic\Bundle\IssueBundle\Entity\Issue;
use OroAcademic\Bundle\IssueBundle\EventListener\IssueEntityListener;

class IssueEntityListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|TopicSender
     */
    protected $sender;

    /**
     * @var IssueEntityListener
     */
    protected $listener;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->sender = $this
            ->getMockBuilder('Oro\Bundle\NavigationBundle\Content\TopicSender')
            ->disableOriginalConstructor()
            ->getMock();

        $this->listener = new IssueEntityListener($this->sender);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->sender, $this->listener);
    }

    public function testPostUpdate()
    {
        $generator = $this
            ->getMockBuilder('Oro\Bundle\NavigationBundle\Content\TagGeneratorChain')
            ->disableOriginalConstructor()
            ->getMock();
        $generator->expects($this->once())->method('generate')->willReturn([]);
        $this->sender->expects($this->once())->method('getGenerator')->willReturn($generator);
        $this->sender->expects($this->once())->method('send');

        /** @var Issue|\PHPUnit_Framework_MockObject_MockObject $orderMock */
        $issueMock = $this->getMock('OroAcademic\Bundle\IssueBundle\Entity\Issue');

        $this->listener->postUpdate($issueMock, $this->getLifecycleEventArgs($issueMock));
    }

    /**
     * @param Issue $issue
     * @return LifecycleEventArgs|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getLifecycleEventArgs(Issue $issue)
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|LifecycleEventArgs $lifecycleEventArgs */
        $lifecycleEventArgs = $this->getMockBuilder('Doctrine\ORM\Event\LifecycleEventArgs')
            ->disableOriginalConstructor()
            ->getMock();
        $lifecycleEventArgs->expects($this->any())
            ->method('getEntity')
            ->willReturn($issue);

        return $lifecycleEventArgs;
    }
}
