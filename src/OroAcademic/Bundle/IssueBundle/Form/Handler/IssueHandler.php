<?php

namespace OroAcademic\Bundle\IssueBundle\Form\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use OroAcademic\Bundle\IssueBundle\Entity\Issue;

class IssueHandler extends AbstractIssueHandler
{

    /**
     * Process form
     *
     * @param Issue $entity
     * @param \Oro\Bundle\UserBundle\Entity\User $currentUser
     * @return bool  True on successful processing, false otherwise
     */
    public function process(Issue $entity, $currentUser)
    {
        $this->checkForm($entity, $currentUser);
        $this->form->setData($entity);
        if (in_array($this->request->getMethod(), ['POST', 'PUT'])) {
            $this->form->submit($this->request);
            if ($this->form->isValid()) {
                $this->onSuccess($entity);
                return true;
            }
        }
        return false;
    }

    /**
     * @param Issue $entity
     * @param $currentUser
     */
    protected function checkForm(Issue $entity, $currentUser)
    {
        if ($entity->getId() || $entity->getParent()) {
            $this->form->remove('type');
        } elseif ($entity->getAssignee()) {
            $entity
                ->setReporter($currentUser);
        } else {
            $entity
                ->setReporter($currentUser)
                ->setAssignee($currentUser);
        }
    }
}
