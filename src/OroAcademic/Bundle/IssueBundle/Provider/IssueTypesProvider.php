<?php
/**
 * Created by PhpStorm.
 * User: vdenchyk
 * Date: 13.04.16
 * Time: 16:30
 */

namespace OroAcademic\Bundle\IssueBundle\Provider;

class IssueTypesProvider
{
    private $issueTypes;

    public function __construct(array $issueTypes)
    {
        $this->issueTypes = $issueTypes;
    }


    /**
     * Types can be: all|main|sub
     *
     * @param string $type
     * @return array
     */
    public function getIssueTypes($type)
    {
        $issueTypes = [];
        foreach ($this->issueTypes as $v) {
            if (in_array($type, ['main', 'sub'])) {
                if ($v['type'] == $type) {
                    $issueTypes[$v['name']] = $v['name'];
                }
            } else {
                $issueTypes[$v['name']] = $v['name'];
            }
        }
        return $issueTypes;
    }
}
