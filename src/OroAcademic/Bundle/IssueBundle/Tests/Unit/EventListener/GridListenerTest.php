<?php

namespace OroAcademic\Bundle\IssueBundle\Tests\Unit\EventListener;

use Doctrine\Common\Collections\ArrayCollection;
use Oro\Bundle\UserBundle\Entity\User;
use OroAcademic\Bundle\IssueBundle\Entity\Issue;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use OroAcademic\Bundle\IssueBundle\EventListener\GridListener;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;

class GridListenerTest extends \PHPUnit_Framework_TestCase
{
    private $dispatcher;
    private $kernel;
    protected function setUp()
    {
        $this->dispatcher = new EventDispatcher();
        $listener = new GridListener();
        $this->dispatcher->addListener(KernelEvents::VIEW, array($listener, 'onKernelView'));
        $this->kernel = $this->getMock('Symfony\Component\HttpKernel\HttpKernelInterface');
    }
    protected function tearDown()
    {
        $this->dispatcher = null;
        $this->kernel = null;
    }

    public function testRelatedIssuesGrid()
    {
        $entity = new Issue();

        $relatedIssues = [
            0 => $this->getEntity('OroAcademic\Bundle\IssueBundle\Entity\Issue', 1),
            1 => $this->getEntity('OroAcademic\Bundle\IssueBundle\Entity\Issue', 2),
        ];
        $entity->setRelatedIssues(new ArrayCollection($relatedIssues));
        $controllerResult = ['entity' => $entity];
        $request = new Request();
        $request->query->set('grid', ['rel-issues-grid' => http_build_query(['i'=>1, 'p'=> 25])]);
        $event = new GetResponseForControllerResultEvent(
            $this->kernel,
            $request,
            HttpKernelInterface::MASTER_REQUEST,
            $controllerResult
        );
        $this->dispatcher->dispatch(KernelEvents::VIEW, $event);
        $expectedGrid = ['rel-issues-grid' => http_build_query(['i'=>1, 'p'=> 25,'g' => ['ids' => [1, 2]]])];
        $resultGrid = $event->getRequest()->query->get('grid');
        $this->assertEquals($expectedGrid, $resultGrid);
    }

    public function testCollaboratorsGrid()
    {
        $entity = new User();

        $controllerResult = ['entity' => $entity];
        $request = new Request();
        $request->query->set('grid', ['collaborators-grid' => http_build_query(['i'=>1, 'p'=> 25])]);
        $event = new GetResponseForControllerResultEvent(
            $this->kernel,
            $request,
            HttpKernelInterface::MASTER_REQUEST,
            $controllerResult
        );
        $this->dispatcher->dispatch(KernelEvents::VIEW, $event);

        $resultGrid = $event->getRequest()->query->get('grid');
        $this->assertNull($resultGrid);
    }

    /**
     * @param string $className
     * @param int|string $idValue
     * @param string $idProperty
     * @return object
     */
    protected function getEntity($className, $idValue, $idProperty = 'id')
    {
        $entity = new $className;

        $reflectionClass = new \ReflectionClass($className);
        $method = $reflectionClass->getProperty($idProperty);
        $method->setAccessible(true);
        $method->setValue($entity, $idValue);

        return $entity;
    }
}
