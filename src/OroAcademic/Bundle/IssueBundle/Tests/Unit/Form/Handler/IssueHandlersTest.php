<?php

namespace OroAcademic\Bundle\IssueBundle\Tests\Unit\Form\Handler;

use OroAcademic\Bundle\IssueBundle\Entity\Issue;
use OroAcademic\Bundle\IssueBundle\Form\Handler\IssueHandler;
use OroAcademic\Bundle\IssueBundle\Form\Handler\IssueApiHandler;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

use Oro\Bundle\UserBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;

class IssueHandlersTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|FormInterface
     */
    protected $form;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ObjectManager
     */
    protected $manager;

    /**
     * @var array
     */
    protected $handlers;

    /**
     * @var Issue
     */
    protected $entity;

    /**
     * @var User
     */
    protected $user;

    protected function setUp()
    {
        $this->form = $this->getMockBuilder('Symfony\Component\Form\Form')
            ->disableOriginalConstructor()
            ->getMock();

        $this->request = new Request();

        $this->manager = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->user = $this->getMockBuilder('Oro\Bundle\UserBundle\Entity\User')
            ->disableOriginalConstructor()
            ->getMock();

        $this->entity  = new Issue();
        $handler = new IssueHandler(
            $this->form,
            $this->request,
            $this->manager
        );

        $handlerApi = new IssueApiHandler(
            $this->form,
            $this->request,
            $this->manager
        );
        $this->handlers = [$handler, $handlerApi];
    }

    public function testProcessUnsupportedRequest()
    {
        $this->form->expects($this->exactly(2))
            ->method('setData')
            ->with($this->entity);

        $this->form->expects($this->never())
            ->method('submit');

        foreach ($this->handlers as $handler) {
            $this->assertFalse($handler->process($this->entity, $this->user));
        }
    }

    /**
     * @dataProvider supportedMethods
     *
     * @param string $method
     */
    public function testProcessSupportedRequest($method)
    {
        $this->request->setMethod($method);

        $this->form->expects($this->any())->method('setData')
            ->with($this->entity);
        $this->form->expects($this->exactly(2))->method('submit')
            ->with($this->request);
        $this->form->expects($this->exactly(2))->method('isValid')
            ->will($this->returnValue(true));

        foreach ($this->handlers as $handler) {
            $this->assertTrue($handler->process($this->entity, $this->user));
        }
    }

    public function supportedMethods()
    {
        return [
            ['POST'],
            ['PUT']
        ];
    }
}
