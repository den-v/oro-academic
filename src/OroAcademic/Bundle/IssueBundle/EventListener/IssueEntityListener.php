<?php
/**
 * Created by PhpStorm.
 * User: vdenchyk
 * Date: 07.04.16
 * Time: 22:33
 */

namespace OroAcademic\Bundle\IssueBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Oro\Bundle\NavigationBundle\Content\TopicSender;
use OroAcademic\Bundle\IssueBundle\Entity\Issue;

class IssueEntityListener
{
    /** @var TopicSender */
    protected $sender;

    /**
     * @param TopicSender      $sender
     */
    public function __construct(TopicSender $sender)
    {
        $this->sender = $sender;
    }

    /**
     * Send Issue collection tag to publisher
     *
     * @param Issue $issue
     * @param LifecycleEventArgs $event
     */
    public function postUpdate(Issue $issue, LifecycleEventArgs $event)
    {
        $generator = $this->sender->getGenerator();
        $data = ['name' => 'OroAcademic_Bundle_IssueBundle_Entity_Issue'];
        $this->sender->send($generator->generate($data, true));
    }
}
