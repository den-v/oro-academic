<?php

namespace OroAcademic\Bundle\IssueBundle\Tests\Functional\Controller\Api\Soap;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

/**
 * @outputBuffering enabled
 * @dbIsolation
 */
class IssueControllerTest extends WebTestCase
{
    /**
     * @var array
     */
    protected $issue = [
        'subject' => 'Soap task',
        'code' => 'BB-8888',
        'description' => 'Soap Issue description',
        'taskPriority' => 'high',
        'reporter' => 1,
        'assignee' => 1,
    ];


    protected function setUp()
    {
        $this->initClient(array(), $this->generateWsseAuthHeader());
        $this->initSoapClient();
    }

    /*/**
     * @return integer
     */
    public function testIssueCreate()
    {
        $result = $this->soapClient->createIssue($this->issue);
        $this->assertTrue((bool) $result, $this->soapClient->__getLastResponse());

        return $result;
    }

    /**
     * @depends issueCreate
     */
    public function issueCget()
    {
        $tasks = $this->soapClient->getIssues();
        $tasks = $this->valueToArray($tasks);
        $this->assertCount(1, $tasks);
    }

    /**
     * @param integer $id
     * @depends issueCreate
     */
    public function issueGet($id)
    {
        $task = $this->soapClient->getIssue($id);
        $task = $this->valueToArray($task);
        $this->assertEquals($this->issue['summary'], $task['summary']);
    }

    /**
     * @param integer $id
     * @depends issueCreate
     */
    public function issueUpdate($id)
    {
        $task =  array_merge($this->issue, ['summary' => 'Updated subject']);

        $result = $this->soapClient->updateIssue($id, $task);
        $this->assertTrue($result);

        $updatedTask = $this->soapClient->getIssue($id);
        $updatedTask = $this->valueToArray($updatedTask);

        $this->assertEquals($task['summary'], $updatedTask['summary']);
    }

    /**
     * @param integer $id
     * @depends issueCreate
     */
    public function issueDelete($id)
    {
        $result = $this->soapClient->deleteIssue($id);
        $this->assertTrue($result);

        $this->setExpectedException('\SoapFault', 'Record with ID "' . $id . '" can not be found');
        $this->soapClient->getIssue($id);
    }
}
