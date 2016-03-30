<?php
/**
 * Created by PhpStorm.
 * User: vdenchyk
 * Date: 30/03/16
 * Time: 20:04
 */

namespace OroAcademic\Bundle\IssueBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Oro\Bundle\NoteBundle\Entity\Note;
use OroAcademic\Bundle\IssueBundle\Entity\Issue;

class NoteListener
{
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (is_a($entity, Note::class)) {
            /** @var Issue $issue */
            $issue = $entity->getTarget();
            if (is_a($issue, Issue::class)) {
                $issue->setUpdatedAt(new \DateTime('now', new \DateTimeZone('UTC')));
            }
        }
    }
}