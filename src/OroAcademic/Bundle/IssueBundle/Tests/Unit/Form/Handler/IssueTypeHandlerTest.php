<?php

namespace OroAcademic\Bundle\IssueBundle\Tests\Unit\Form\Handler;

use OroAcademic\Bundle\IssueBundle\Entity\Issue;
use OroAcademic\Bundle\IssueBundle\Form\Handler\IssueHandler;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

use Oro\Bundle\UserBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;

class IssueHandlerTest extends \PHPUnit_Framework_TestCase
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
     * @var IssueHandler
     */
    protected $handler;

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
        $this->handler = new IssueHandler(
            $this->form,
            $this->request,
            $this->manager
        );
    }

    public function testProcessUnsupportedRequest()
    {
        $this->form->expects($this->once())
            ->method('setData')
            ->with($this->entity);

        $this->form->expects($this->never())
            ->method('submit');

        $this->assertFalse($this->handler->process($this->entity, $this->user));
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
        $this->form->expects($this->once())->method('submit')
            ->with($this->request);
        $this->form->expects($this->once())->method('isValid')
            ->will($this->returnValue(true));

        $this->assertTrue($this->handler->process($this->entity, $this->user));
    }

    public function supportedMethods()
    {
        return [
            ['POST'],
            ['PUT']
        ];
    }
}
