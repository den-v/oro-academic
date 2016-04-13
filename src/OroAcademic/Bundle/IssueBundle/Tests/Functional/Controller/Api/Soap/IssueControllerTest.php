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
        'code' => 'BB-8888',
        'summary' => 'Soap task',
        'description' => 'Soap Issue description',
        'type' => 'Bug',
        'priority' => 'low',
        'reporter' => 1,
        'assignee' => 1,
    ];


    protected function setUp()
    {
        $this->initClient(array(), $this->generateWsseAuthHeader());
        $this->initSoapClient();
    }

    /**
     * @return integer
     */
    public function testCreate()
    {
        $result = $this->soapClient->createIssue($this->issue);
        $this->assertTrue((bool) $result, $this->soapClient->__getLastResponse());
        
        return $result;
    }

    /**
     * @depends testCreate
     */
    public function testCget()
    {
        $issues = $this->soapClient->getIssues();
        $issues = $this->valueToArray($issues);
        $this->assertCount(1, $issues);
    }

    /**
     * @param integer $id
     * @depends testCreate
     */
    public function testGet($id)
    {
        $issue = $this->soapClient->getIssue($id);
        $issue = $this->valueToArray($issue);
        $this->assertEquals($this->issue['summary'], $issue['summary']);
    }

    /**
     * @param integer $id
     * @depends testCreate
     */
    public function testUpdate($id)
    {
        $issue =  array_merge($this->issue, ['summary' => 'Updated summary']);

        $result = $this->soapClient->updateIssue($id, $issue);
        $this->assertTrue($result);

        $updatedIssue = $this->soapClient->getIssue($id);
        $updatedIssue = $this->valueToArray($updatedIssue);

        $this->assertEquals($issue['summary'], $updatedIssue['summary']);
    }

    /**
     * @param integer $id
     * @depends testCreate
     */
    public function testDelete($id)
    {
        $result = $this->soapClient->deleteIssue($id);
        $this->assertTrue($result);

        $this->setExpectedException('\SoapFault', 'Record with ID "' . $id . '" can not be found');
        $this->soapClient->getIssue($id);
    }
}
