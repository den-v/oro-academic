<?php
/**
 * Created by PhpStorm.
 * User: vdenchyk
 * Date: 30/03/16
 * Time: 12:15
 */

namespace OroAcademic\Bundle\IssueBundle\Entity;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Doctrine\Common\Collections\ArrayCollection;
use Oro\Bundle\SoapBundle\Entity\SoapEntityInterface;

/**
 * @Soap\Alias("OroAcademic.Bundle.IssueBundle.Entity.Issue")
 */
class IssueSoap extends Issue implements SoapEntityInterface
{
    /**
    * @Soap\ComplexType("int", nillable=true)
    */
    protected $id;

    /**
     * @Soap\ComplexType("string", nillable=true)
     */
    protected $summary;

    /**
     * @Soap\ComplexType("string", nillable=true)
     */
    protected $code;

    /**
     * @Soap\ComplexType("string", nillable=true)
     */
    protected $description;

    /**
     * @Soap\ComplexType("string", nillable=true)
     */
    protected $type;

    /**
     * @Soap\ComplexType("string", nillable=true)
     */
    protected $priority;

    /**
     * @Soap\ComplexType("string", nillable=true)
     */
    protected $resolution;

    /**
     * @Soap\ComplexType("int", nillable=true)
     */
    protected $reporter;

    /**
     * @Soap\ComplexType("int", nillable=true)
     */
    protected $assignee;

    /**
     * @Soap\ComplexType("int", nillable=true)
     */
    protected $parent;

    /**
     * @Soap\ComplexType("int[]", nillable=true)
     */
    protected $children;
    /**
     * @Soap\ComplexType("dateTime", nillable=true)
     */
    protected $createdAt;

    /**
     * @Soap\ComplexType("dateTime", nillable=true)
     */
    protected $updatedAt;

    /**
     * @Soap\ComplexType("int", nillable=true)
     */
    protected $workflowItem;

    /**
     * @Soap\ComplexType("int", nillable=true)
     */
    protected $workflowStep;

    /**
     * @Soap\ComplexType("int[]", nillable=true)
     */
    protected $relatedIssues;

    /**
     * Init soap entity with original entity
     *
     * @param Issue $issue
     */
    public function soapInit($issue)
    {
        $this->id = $issue->id;
        $this->summary = $issue->summary;
        $this->code = $issue->code;
        $this->description = $issue->description;
        $this->type = $issue->type;
        $this->priority = $issue->priority ? $issue->priority->getName() : null;
        $this->resolution = $issue->resolution ? $issue->resolution->getName() : null;
        $this->reporter = $this->getEntityId($issue->reporter);
        $this->assignee = $this->getEntityId($issue->assignee);
        $this->parent = $this->getEntityId($issue->parent);
        $this->children = $this->cgetEntityId($issue->children);
        $this->createdAt = $issue->createdAt;
        $this->updatedAt = $issue->updatedAt;
        $this->workflowItem = $this->getEntityId($issue->workflowItem);
        $this->workflowStep = $this->getEntityId($issue->workflowStep);
        //$this->relatedIssues = $this->cgetEntityId($issue->relatedIssues);
    }

    /**
     * @param object $entity
     *
     * @return integer|null
     */
    protected function getEntityId($entity)
    {
        if ($entity) {
            return $entity->getId();
        }
        return null;
    }

    /**
     * @param ArrayCollection $entities
     *
     * @return array|null
     */
    protected function cgetEntityId($entities)
    {
        if ($entities) {
            $ids = [];
            foreach ($entities as $entity) {
                $ids[] = $entity->getId();
            }
            return $ids;
        }
        return null;
    }
}
