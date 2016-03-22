<?php

namespace OroAcademic\Bundle\IssueBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;

use Oro\Bundle\UserBundle\Entity\User;
use OroAcademic\Bundle\IssueBundle\Entity\Issue;

/**
 * @Route("/issue")
 */
class IssueController extends Controller
{
    /**
     * @Route( name="oroacademic_issue_index" )
     * @Acl(
     *      id="oroacademic_issue_view",
     *      type="entity",
     *      class="OroAcademicIssueBundle:Issue",
     *      permission="VIEW"
     * )
     * @Template
     */

    public function indexAction()
    {
        return [
            'entity_class' => $this->container->getParameter('oroacademic_issue.entity.class')
        ];
    }

    /**
     * @Route("/view/{id}", name="oroacademic_issue_view", requirements={"id"="\d+"})
     * @AclAncestor("oroacademic_issue_view")
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
     */
    public function updateAction(Issue $issue)
    {

    }

    /**
     * @Route("/delete/{id}", name="oroacademic_issue_delete", requirements={"id"="\d+"})
     * @Acl(
     *      id="oroacademic_issue_delete",
     *      type="entity",
     *      class="OroAcademicIssueBundle:Issue",
     *      permission="DELETE"
     * )
     */
    public function deleteAction(Issue $issue)
    {

    }
}
