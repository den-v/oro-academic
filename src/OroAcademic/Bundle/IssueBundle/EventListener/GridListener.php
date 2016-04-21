<?php

namespace OroAcademic\Bundle\IssueBundle\EventListener;

use Oro\Bundle\UserBundle\Entity\User;
use OroAcademic\Bundle\IssueBundle\Entity\Issue;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class GridListener
{
    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        //result returned by the controller
        $data = $event->getControllerResult();

        if (isset($data['entity'])) {
            $entity = $data['entity'];
            $grid = $event->getRequest()->query->get('grid');
            if (isset($grid['rel-issues-grid']) && $entity instanceof Issue) {
                parse_str($grid['rel-issues-grid'], $arr);
                foreach ($entity->getRelatedIssues() as $related) {
                    $arr['g']['ids'][] = $related->getId();
                }
                $event->getRequest()->query->set('grid', ['rel-issues-grid' => http_build_query($arr)]);
            } elseif (isset($grid['collaborators-grid']) && $entity instanceof User) {
                    $event->getRequest()->query->remove('grid');
            }
        }
    }
}
