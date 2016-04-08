<?php

namespace OroAcademic\Bundle\IssueBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use Oro\Bundle\EntityBundle\EntityProperty\DatesAwareInterface;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;

use Oro\Bundle\UserBundle\Entity\User;
use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\WorkflowBundle\Entity\WorkflowItem;
use Oro\Bundle\WorkflowBundle\Entity\WorkflowStep;

use OroAcademic\Bundle\IssueBundle\Model\ExtendIssue;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\EntityListeners({"OroAcademic\Bundle\IssueBundle\EventListener\IssueEntityListener"})
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
 *      routeCreate="oroacademic_issue_create",
 *      defaultValues={
 *          "security"={
 *              "type"="ACL",
 *              "group_name"=""
 *          },
 *          "dataaudit"={
 *              "auditable"=true
 *          },
 *          "ownership"={
 *              "owner_type"="USER",
 *              "owner_field_name"="reporter",
 *              "owner_column_name"="reporter_id",
 *              "organization_field_name"="organization",
 *              "organization_column_name"="organization_id"
 *          },
 *          "grouping"={
 *              "groups"={"activity"}
 *          },
 *          "note"={
 *              "enabled"=true
 *          },
 *          "tag"={
 *              "enabled"=true
 *          },
 *          "grid"={
 *              "default"="issues-grid",
 *          },
 *          "workflow"={
 *              "active_workflow"="oroacademic_issue_flow"
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
     * @ConfigField(
     *    defaultValues={
     *        "importexport"={
     *            "order"="0"
     *        }
     *    }
     * )
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="summary", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message = "Summary can't be blank")
     * @ConfigField(
     *      defaultValues={
     *          "dataaudit"={
     *              "auditable"=true
     *          },
     *          "importexport"={
     *              "header"="Summary",
     *              "order"="30"
     *          }
     *      }
     * )
     */
    protected $summary;

    /**
     * @ORM\Column(name="code", type="string", length=32, unique=true)
     * @Assert\NotBlank(message = "Code can't be blank")
     * @ConfigField(
     *      defaultValues={
     *          "dataaudit"={
     *              "auditable"=true
     *          },
     *          "importexport"={
     *              "header"="Code",
     *              "order"="20"
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
     *          },
     *          "importexport"={
     *              "header"="Description",
     *              "order"="40"
     *          }
     *      }
     * )
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=32, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Regex("/^(Bug|Task|Subtask|Story)/")
     * @ConfigField(
     *      defaultValues={
     *          "dataaudit"={
     *              "auditable"=true
     *          },
     *          "importexport"={
     *              "header"="Type",
     *              "order"="50"
     *          }
     *      }
     * )
     */
    protected $type;
    /**
     * @var IssuePriority
     *
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="IssuePriority")
     * @ORM\JoinColumns({ @ORM\JoinColumn(name="priority", referencedColumnName="name") })
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"="60"
     *          }
     *      }
     * )
     */
    protected $priority;
    /**
     * @var IssueResolution
     *
     * @ORM\ManyToOne(targetEntity="IssueResolution")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="resolution", referencedColumnName="name")
     * })
     * @ConfigField(
     *  defaultValues={
     *      "importexport"={
     *          "excluded"=true
     *      }
     *  }
     * )
     */
    protected $resolution;

    /**
     * @var User
     *
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="reporter_id", referencedColumnName="id", onDelete="SET NULL")
     * @ConfigField(
     *      defaultValues={
     *          "dataaudit"={
     *              "auditable"=true
     *          },
     *          "importexport"={
     *              "header"="Reporter",
     *              "order"="70"
     *          }
     *      }
     * )
     */
    protected $reporter;

    /**
     * @var User
     *
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="assignee_id", referencedColumnName="id", onDelete="SET NULL")
     * @ConfigField(
     *      defaultValues={
     *          "dataaudit"={
     *              "auditable"=true
     *          },
     *          "importexport"={
     *              "header"="Assignee",
     *              "order"="80"
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
     * @ConfigField(
     *  defaultValues={
     *          "importexport"={
     *              "order"="90"
     *          }
     *  }
     * )
     */
    protected $parent;

    /**
     * @var Issue
     *
     * @ORM\OneToMany(targetEntity="Issue", mappedBy="parent")
     * @ConfigField(
     *  defaultValues={
     *      "importexport"={
     *          "excluded"=true
     *      }
     *  }
     * )
     */
    protected $children;

    /**
     * @var WorkflowItem
     *
     * @ORM\OneToOne(targetEntity="Oro\Bundle\WorkflowBundle\Entity\WorkflowItem")
     * @ORM\JoinColumn(name="workflow_item_id", referencedColumnName="id", onDelete="SET NULL")
     * @ConfigField(
     *  defaultValues={
     *      "importexport"={
     *          "excluded"=true
     *      }
     *  }
     * )
     */
    protected $workflowItem;

    /**
     * @var WorkflowStep
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\WorkflowBundle\Entity\WorkflowStep")
     * @ORM\JoinColumn(name="workflow_step_id", referencedColumnName="id", onDelete="SET NULL")
     * @ConfigField(
     *  defaultValues={
     *      "importexport"={
     *          "excluded"=true
     *      }
     *  }
     * )
     */
    protected $workflowStep;

    /**
     * @ORM\ManyToMany(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinTable(name="oroacademic_issue_collaborator",
     *      joinColumns={@ORM\JoinColumn(name="issue_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     *      )
     * @ConfigField(
     *  defaultValues={
     *      "importexport"={
     *          "excluded"=true
     *      }
     *  }
     * )
     */
    protected $collaborators;

    /**
     * @ORM\ManyToMany(targetEntity="Issue", inversedBy="issuesRelated")
     * @ORM\JoinTable(name="oroacademic_issue_related")
     * @ConfigField(
     *  defaultValues={
     *      "importexport"={
     *          "excluded"=true
     *      }
     *  }
     * )
     **/
    protected $relatedIssues;

    /**
     * @ORM\ManyToMany(targetEntity="Issue", mappedBy="relatedIssues")
     * @ConfigField(
     *  defaultValues={
     *      "importexport"={
     *          "excluded"=true
     *      }
     *  }
     * )
     */
    protected $issuesRelated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @ConfigField(
     *      defaultValues={
     *          "entity"={
     *              "label"="oro.ui.created_at"
     *          },
     *          "importexport"={
     *              "excluded"=true
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
     *          },
     *          "importexport"={
     *              "excluded"=true
     *          }
     *      }
     * )
     */
    protected $updatedAt;

    /**
     * @var bool
     */
    protected $updatedAtSet;

    /**
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\OrganizationBundle\Entity\Organization")
     * @ORM\JoinColumn(name="organization_id", referencedColumnName="id")
     * )
     * @ConfigField(
     *    defaultValues={
     *        "importexport"={
     *            "header"="Organization",
     *            "order"="100"
     *        }
     *    }
     * )
     */
    protected $organization;

    public function __construct()
    {
        parent::__construct();

        $this->collaborators = new ArrayCollection();
        $this->relatedIssues = new ArrayCollection();
        $this->children = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set organization
     *
     * @param \Oro\Bundle\OrganizationBundle\Entity\Organization $organization
     *
     * @return Issue
     */
    public function setOrganization(Organization $organization = null)
    {
        $this->organization = $organization;
        return $this;
    }
    /**
     * Get organization
     *
     * @return \Oro\Bundle\OrganizationBundle\Entity\Organization
     */
    public function getOrganization()
    {
        return $this->organization;
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
     * @return Issue
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
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
     * BugFix in platform OroUIBundle::macros.html.twig line 890
     *
     * @return User
     */
    public function getOwner()
    {
        return $this->reporter;
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
     * @return Issue
     */
    public function setReporter($reporter)
    {
        $this->reporter = $reporter;
        return $this;
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
     * @return Issue
     */
    public function setAssignee($assignee)
    {
        $this->assignee = $assignee;
        return $this;
    }

    /**
     * Get parent
     *
     * @return Issue
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set parent
     *
     * @param Issue $parent
     *
     * @return Issue
     */
    public function setParent(Issue $parent = null)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param Issue $child
     * @return Issue
     */
    public function addChild(Issue $child)
    {
        $this->children[] = $child;
        return $this;
    }

    /**
     * @param Issue $child
     */
    public function removeChild(Issue $child)
    {
        $this->children->removeElement($child);
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
     * @return Issue
     */
    public function setWorkflowItem($workflowItem)
    {
        $this->workflowItem = $workflowItem;

        return $this;
    }

    /**
     * @return WorkflowStep
     */
    public function getWorkflowStep()
    {
        return $this->workflowStep;
    }

    /**
     * @param WorkflowItem $workflowStep
     * @return Issue
     */
    public function setWorkflowStep($workflowStep)
    {
        $this->workflowStep = $workflowStep;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getCollaborators()
    {
        return $this->collaborators;
    }

    /**
     * Add Collaborator
     * 
     * @param User $collaborator
     */
    public function addCollaborator(User $collaborator)
    {
        $collaborators = $this->getCollaborators();
        if (!$collaborators->contains($collaborator)) {
            $this->collaborators->add($collaborator);
        }
    }

    /**
     * Remove Collaborator
     *
     * @param User $collaborator
     */
    public function removeCollaborator(User $collaborator)
    {
        $this->collaborators->removeElement($collaborator);
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

    /**
     * Add relatedIssues
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $relatedIssues
     *
     * @return Issue
     */
    public function setRelatedIssues(ArrayCollection $relatedIssues)
    {
        $this->relatedIssues = $relatedIssues;
        return $this;
    }

    /**
     * Get relatedIssues
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRelatedIssues()
    {
        return $this->relatedIssues;
    }

    /**
     * Remove relatedIssue
     *
     * @param Issue $relatedIssue
     */
    public function removeRelatedIssue(Issue $relatedIssue)
    {
        $this->relatedIssues->removeElement($relatedIssue);
    }

    /**
     * Get relatedIssues
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIssuesRelated()
    {
        return $this->issuesRelated;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getCode().": ".$this->getSummary();
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime('now', new \DateTimeZone('UTC'));
        $this->preUpdate();
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime('now', new \DateTimeZone('UTC'));
    }

    public function getClass()
    {
        return static::class;
    }
}
