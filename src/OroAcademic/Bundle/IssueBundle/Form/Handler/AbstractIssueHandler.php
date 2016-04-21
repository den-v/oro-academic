<?php

namespace OroAcademic\Bundle\IssueBundle\Form\Handler;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use OroAcademic\Bundle\IssueBundle\Entity\Issue;

abstract class AbstractIssueHandler
{
    /**
     * @var FormInterface
     */
    protected $form;
    /**
     * @var Request
     */
    protected $request;
    /**
     * @var ObjectManager
     */
    protected $manager;
    
    /**
     * @param FormInterface       $form
     * @param Request             $request
     * @param ObjectManager       $manager
     */
    public function __construct(
        FormInterface $form,
        Request $request,
        ObjectManager $manager
    ) {
        $this->form                = $form;
        $this->request             = $request;
        $this->manager             = $manager;
    }

    /**
     * Process form
     *
     * @param Issue $entity
     * @param \Oro\Bundle\UserBundle\Entity\User $currentUser
     * @return bool  True on successful processing, false otherwise
     */
    abstract public function process(Issue $entity, $currentUser);

    /**
     * "Success" form handler
     *
     * @param Issue $entity
     */
    protected function onSuccess(Issue $entity)
    {
        $this->manager->persist($entity);
        $this->manager->flush();
    }

    /**
     * Get form, that build into handler, via handler service
     *
     * @return FormInterface
     */
    public function getForm()
    {
        return $this->form;
    }
}
