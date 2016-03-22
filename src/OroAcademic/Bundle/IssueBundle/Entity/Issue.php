<?php

namespace OroAcademic\Bundle\IssueBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use Oro\Bundle\EntityBundle\EntityProperty\DatesAwareInterface;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;

use Oro\Bundle\UserBundle\Entity\User;
use Oro\Bundle\WorkflowBundle\Entity\WorkflowItem;
use Oro\Bundle\WorkflowBundle\Entity\WorkflowStep;

use OroAcademic\Bundle\IssueBundle\Model\ExtendIssue;

/**
 * @ORM\Entity
 * @ORM\Table(
 *      name="oroacademic_issue",
 *      indexes={
 *          @ORM\Index(name="issue_updated_at_idx",columns={"updatedAt"}),
 *      }
 * )
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="OroAcademic\Bundle\IssueBundle\Entity\Repository\IssueRepository")
 * @Config(
 *      routeName="oroacademic_issue_index",
 *      routeView="oroacademic_issue_view",
 *      defaultValues={
 *          "security"={
 *              "type"="ACL"
 *          },
 *          "dataaudit"={
 *              "auditable"=true
 *          },
 *          "grouping"={
 *              "groups"={"activity"}
 *          },
 *          "tag"={
 *              "enabled"=true
 *          },
 *          "grid"={
 *              "default"="issues-grid",
 *          }
 *      }
 * )
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
class Issue extends ExtendIssue implements DatesAwareInterface
{

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="summary", type="string", length=255, nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "dataaudit"={
     *              "auditable"=true
     *          }
     *      }
     * )
     */
    protected $summary;

    /**
     * @ORM\Column(name="code", type="string", length=32, unique=true)
     * @ConfigField(
     *      defaultValues={
     *          "dataaudit"={
     *              "auditable"=true
     *          }
     *      }
     * )
     */
    protected $code;
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "dataaudit"={
     *              "auditable"=true
     *          }
     *      }
     * )
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=32, nullable=false)
     * @ConfigField(
     *      defaultValues={
     *          "dataaudit"={
     *              "auditable"=true
     *          }
     *      }
     * )
     */
    protected $type;
    /**
     * @var IssuePriority
     *
     * @ORM\ManyToOne(targetEntity="IssuePriority")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="priority", referencedColumnName="name")
     * })
     */
    protected $priority;
    /**
     * @var IssueResolution
     *
     * @ORM\ManyToOne(targetEntity="IssueResolution")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="resolution", referencedColumnName="name")
     * })
     */
    protected $resolution;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="reporter_id", referencedColumnName="id", onDelete="SET NULL")
     * @ConfigField(
     *      defaultValues={
     *          "dataaudit"={
     *              "auditable"=true
     *          }
     *      }
     * )
     */
    protected $reporter;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="assignee_id", referencedColumnName="id", onDelete="SET NULL")
     * @ConfigField(
     *      defaultValues={
     *          "dataaudit"={
     *              "auditable"=true
     *          }
     *      }
     * )
     */
    protected $assignee;

    /**
     * @var Issue
     *
     * @ORM\ManyToOne(targetEntity="Issue", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $parent;

    /**
     * @var Issue
     *
     * @ORM\OneToMany(targetEntity="Issue", mappedBy="parent")
     */
    protected $children;

    /**
     * @var WorkflowItem
     *
     * @ORM\OneToOne(targetEntity="Oro\Bundle\WorkflowBundle\Entity\WorkflowItem")
     * @ORM\JoinColumn(name="workflow_item_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $workflowItem;

    /**
     * @var WorkflowStep
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\WorkflowBundle\Entity\WorkflowStep")
     * @ORM\JoinColumn(name="workflow_step_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $workflowStep;

    /**
     * @var Collection
     */
    protected $collaborators;

    /**
     * @var Collection
     */
    protected $related_issues;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @ConfigField(
     *      defaultValues={
     *          "entity"={
     *              "label"="oro.ui.created_at"
     *          }
     *      }
     * )
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @ConfigField(
     *      defaultValues={
     *          "entity"={
     *              "label"="oro.ui.updated_at"
     *          }
     *      }
     * )
     */
    protected $updatedAt;

    /**
     * @var bool
     */
    protected $updatedAtSet;


    public function __construct()
    {
        parent::__construct();

        $this->collaborators = new ArrayCollection();
        $this->related_issues = new ArrayCollection();
    }
    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param int $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @param string $summary
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return IssuePriority
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param IssuePriority $priority
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    /**
     * @return IssueResolution
     */
    public function getResolution()
    {
        return $this->resolution;
    }

    /**
     * @param IssueResolution $resolution
     */
    public function setResolution($resolution)
    {
        $this->resolution = $resolution;
    }

    /**
     * @return User
     */
    public function getReporter()
    {
        return $this->reporter;
    }

    /**
     * @param User $reporter
     */
    public function setReporter($reporter)
    {
        $this->reporter = $reporter;
    }

    /**
     * @return User
     */
    public function getAssignee()
    {
        return $this->assignee;
    }

    /**
     * @param User $assignee
     */
    public function setAssignee($assignee)
    {
        $this->assignee = $assignee;
    }

    /**
     * @return int
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param int $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return int
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param int $children
     */
    public function setChildren($children)
    {
        $this->children = $children;
    }

    /**
     * @return WorkflowItem
     */
    public function getWorkflowItem()
    {
        return $this->workflowItem;
    }

    /**
     * @param WorkflowItem $workflowItem
     */
    public function setWorkflowItem($workflowItem)
    {
        $this->workflowItem = $workflowItem;
    }

    /**
     * @return WorkflowStep
     */
    public function getWorkflowStep()
    {
        return $this->workflowStep;
    }

    /**
     * @param WorkflowStep $workflowStep
     */
    public function setWorkflowStep($workflowStep)
    {
        $this->workflowStep = $workflowStep;
    }

    /**
     * @return Collection
     */
    public function getCollaborators()
    {
        return $this->collaborators;
    }

    /**
     * @param Collection $collaborators
     */
    public function setCollaborators($collaborators)
    {
        $this->collaborators = $collaborators;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return mixed
     */
    public function setCreatedAt(\DateTime $createdAt = null)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     * @return mixed
     */
    public function setUpdatedAt(\DateTime $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return bool
     */
    public function isUpdatedAtSet()
    {
        return $this->updatedAtSet;
    }
}
