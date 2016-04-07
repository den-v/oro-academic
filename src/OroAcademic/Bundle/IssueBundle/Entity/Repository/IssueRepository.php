<?php

namespace OroAcademic\Bundle\IssueBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

use Oro\Bundle\SecurityBundle\ORM\Walker\AclHelper;
use OroAcademic\Bundle\IssueBundle\Entity\Issue;

class IssueRepository extends EntityRepository
{
    /**
     * Get opportunities by state by current quarter
     *
     * @param $aclHelper AclHelper
     * @param  array     $dateRange
     * @return array
     */
    public function getIssuesByStatus(AclHelper $aclHelper, $dateRange)
    {
        $dateEnd = $dateRange['end'];
        $dateStart = $dateRange['start'];

        return $this->getIssuesDataByStatus($aclHelper, $dateStart, $dateEnd);
    }

    /**
     * @param  AclHelper $aclHelper
     * @param $dateStart
     * @param $dateEnd
     * @return array
     */
    protected function getIssuesDataByStatus(AclHelper $aclHelper, $dateStart = null, $dateEnd = null)
    {
        // select all possible statuses
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('status.name, status.label')
            ->from('OroWorkflowBundle:WorkflowStep', 'status')
            ->where('status.definition = \'oroacademic_issue_flow\'')
            ->orderBy('status.name', 'ASC');

        $resultData = array();
        $data = $qb->getQuery()->getArrayResult();
        foreach ($data as $status) {
            $name = $status['name'];
            $label = $status['label'];
            $resultData[$name] = array(
                'name' => $name,
                'label' => $label,
                'issues' => 0,
            );
        }

        // select issues data
        $qb = $this->createQueryBuilder('issue');
        $qb->select('COUNT(issue.id) as issues, step.name as status')
            ->leftJoin('issue.workflowStep', 'step')
            ->groupBy('step.label');

        if ($dateStart && $dateEnd) {
            $qb->where($qb->expr()->between('issue.createdAt', ':dateFrom', ':dateTo'))
                ->setParameter('dateFrom', $dateStart)
                ->setParameter('dateTo', $dateEnd);
        }
        $groupedData = $aclHelper->apply($qb)->getArrayResult();

        foreach ($groupedData as $statusData) {
            $status = $statusData['status'];
            $issues = (int)$statusData['issues'];
            if ($issues) {
                $resultData[$status]['issues'] = $issues;
            }
        }

        return $resultData;
    }

}
