<?php

namespace OroAcademic\Bundle\IssueBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;

use OroAcademic\Bundle\IssueBundle\Entity\Issue;

/**
 * @Route("/issue")
 */
class IssueController extends Controller
{
    /**
     * @Route( name="oroacademic_issue_index" )
     * @AclAncestor("oroacademic_issue_view")
     * @Template
     */

    public function indexAction()
    {
        return [
            'entity_class' => $this->container->getParameter('oroacademic_issue.entity.class')
        ];
    }

    /**
     * @Route("/create", name="oroacademic_issue_create")
     * @Acl(
     *      id="oroacademic_issue_create",
     *      type="entity",
     *      class="OroAcademicIssueBundle:Issue",
     *      permission="CREATE"
     * )
     * @Template("OroAcademicIssueBundle:Issue:update.html.twig")
     */
    public function createAction()
    {
        $issue = new Issue();

        $formAction = $this->get('oro_entity.routing_helper')
            ->generateUrlByRequest('oroacademic_issue_create', $this->get('request_stack')->getCurrentRequest());

        return $this->formRender($issue, $formAction);
    }


    /**
     * @Route("/view/{id}", name="oroacademic_issue_view", requirements={"id"="\d+"})
     * @Acl(
     *      id="oroacademic_issue_view",
     *      type="entity",
     *      class="OroAcademicIssueBundle:Issue",
     *      permission="VIEW"
     * )
     * @Template
     */
    public function viewAction(Issue $issue)
    {
        return array('entity' => $issue);
    }

    /**
     * @Route("/update/{id}", name="oroacademic_issue_update", requirements={"id"="\d+"})
     * @Template
     * @Acl(
     *      id="oroacademic_issue_update",
     *      type="entity",
     *      class="OroAcademicIssueBundle:Issue",
     *      permission="EDIT"
     * )
     * @param Issue $issue
     * @return array
     */
    public function updateAction(Issue $issue)
    {
        $formAction = $this->get('oro_entity.routing_helper')
            ->generateUrlByRequest('oroacademic_issue_update', $this->get('request_stack')->getCurrentRequest(), ['id' => $issue->getId()]);

        return $this->formRender($issue, $formAction);
    }

    /**
     * @param Issue $issue
     * @param string $formAction
     * @return array
     */
    protected function formRender(Issue $issue, $formAction)
    {
        $saved = false;
        if ($this->get('oroacademic_issue.form.handler.issue')->process($issue, $this->getUser())) {
            if (!$this->get('request_stack')->getCurrentRequest()->get('_widgetContainer')) {
                $this->get('session')->getFlashBag()->add(
                    'success',
                    $this->get('translator')->trans('oroacademic.issue.saved_message')
                );

                return $this->get('oro_ui.router')->redirectAfterSave(
                    array(
                        'route' => 'oroacademic_issue_update',
                        'parameters' => array('id' => $issue->getId()),
                    ),
                    array(
                        'route' => 'oroacademic_issue_view',
                        'parameters' => array('id' => $issue->getId()),
                    )
                );
            }
            $saved = true;
        }
        $form = $this->get('oroacademic_issue.form.handler.issue')->getForm()->createView();
        return array(
            'entity'     => $issue,
            'saved'      => $saved,
            'form'       => $form,
            'formAction' => $formAction,
        );
    }

}
