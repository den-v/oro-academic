<?php

namespace OroAcademic\Bundle\IssueBundle\Tests\Functional\Controller\Api\Rest;

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
        'code' => 'BB-9999',
        'summary' => 'Rest Issue',
        'description' => 'Rest Issue description',
        'type' => 'Bug',
        'priority' => 'low',
        'reporter' => 1,
        'assignee' => 1,
    ];

    protected function setUp()
    {
        $this->initClient([], $this->generateWsseAuthHeader());
    }

    public function testCreate()
    {
        $this->client->request('POST', $this->getUrl('oroacademic_api_post_issue'), ['issue' => $this->issue]);
        $issue = $this->getJsonResponseContent($this->client->getResponse(), 201);

        return $issue['id'];
    }

    /**
     * @depends testCreate
     */
    public function testCget()
    {
        $this->client->request('GET', $this->getUrl('oroacademic_api_get_issues'));
        $issues = $this->getJsonResponseContent($this->client->getResponse(), 200);

        $this->assertCount(1, $issues);
    }

    /**
     * @depends testCreate
     *
     * @param integer $id
     */
    public function testGet($id)
    {
        $this->client->request('GET', $this->getUrl('oroacademic_api_get_issue', ['id' => $id]));
        $issue = $this->getJsonResponseContent($this->client->getResponse(), 200);

        $this->assertEquals($this->issue['summary'], $issue['summary']);
    }

    /**
     * @depends testCreate
     *
     * @param integer $id
     */
    public function testPut($id)
    {
        $updatedIssue = array_merge($this->issue, ['summary' => 'Updated summary']);
        $this
            ->client
            ->request('PUT', $this->getUrl('oroacademic_api_put_issue', ['id' => $id]), ['issue' => $updatedIssue]);
        $result = $this->client->getResponse();
        $this->assertEmptyResponseStatusCodeEquals($result, 204);

        $this->client->request('GET', $this->getUrl('oroacademic_api_get_issue', ['id' => $id]));

        $issue = $this->getJsonResponseContent($this->client->getResponse(), 200);

        $this->assertEquals($issue['summary'], $updatedIssue['summary']);
    }

    /**
     * @depends testCreate
     *
     * @param integer $id
     */
    public function testDelete($id)
    {
        $this->client->request('DELETE', $this->getUrl('oroacademic_api_delete_issue', ['id' => $id]));
        $result = $this->client->getResponse();
        $this->assertEmptyResponseStatusCodeEquals($result, 204);

        $this->client->request('GET', $this->getUrl('oroacademic_api_get_issue', ['id' => $id]));
        $result = $this->client->getResponse();
        $this->assertJsonResponseStatusCodeEquals($result, 404);
    }
}
