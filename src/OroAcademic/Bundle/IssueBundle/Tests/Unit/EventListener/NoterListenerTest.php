<?php

namespace OroAcademic\Bundle\IssueBundle\Tests\Unit\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use OroAcademic\Bundle\IssueBundle\EventListener\NoteListener;
use Oro\Bundle\NoteBundle\Entity\Note;

class NoteListenerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var NoteListener
     */
    protected $listener;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->listener = new NoteListener();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->listener);
    }

    public function testPrePersist()
    {
        $issueMock = $this->getMock('OroAcademic\Bundle\IssueBundle\Entity\Issue');
        $issueMock->expects($this->once())->method('setUpdatedAt');
        $noteMock = $this->getMock('Oro\Bundle\NoteBundle\Entity\Note');
        $noteMock->expects($this->once())->method('getTarget')->willReturn($issueMock);

        $this->listener->prePersist($this->getLifecycleEventArgs($noteMock));
    }

    /**
     * @param Note | \PHPUnit_Framework_MockObject_MockObject $note
     * @return LifecycleEventArgs|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getLifecycleEventArgs(Note $note)
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|LifecycleEventArgs $lifecycleEventArgs */
        $lifecycleEventArgs = $this->getMockBuilder('Doctrine\ORM\Event\LifecycleEventArgs')
            ->disableOriginalConstructor()
            ->getMock();
        $lifecycleEventArgs->expects($this->once())
            ->method('getEntity')
            ->willReturn($note);

        return $lifecycleEventArgs;
    }
}
