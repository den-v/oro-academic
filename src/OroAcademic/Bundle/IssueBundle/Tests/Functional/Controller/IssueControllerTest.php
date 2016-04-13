<?php

namespace OroAcademic\Bundle\IssueBundle\Tests\Functional\Controller;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

/**
 * @outputBuffering enabled
 * @dbIsolation
 * @dbReindex
 */
class IssueControllerTest extends WebTestCase
{
    protected function setUp()
    {
        $this->initClient([], $this->generateBasicAuthHeader());
    }

    public function testCreate()
    {
        $crawler = $this->client->request('GET', $this->getUrl('oroacademic_issue_create'));
        $form = $crawler->selectButton("Save and Close")->form();
        $form['oroacademic_issue[code]'] = 'BB-7777';
        $form['oroacademic_issue[summary]'] = 'New task';
        $form['oroacademic_issue[type]'] = 'Bug';
        $form['oroacademic_issue[description]'] = 'New description';
        $form['oroacademic_issue[priority]'] = 'low';
        $form['oroacademic_issue[assignee]'] = '1';

        $this->client->followRedirects(true);
        $crawler = $this->client->submit($form);

        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertContains("Issue Successfully Saved", $crawler->html());
    }

    /**
     * @depends testCreate
     */
    public function testUpdate()
    {
        $response = $this->client->requestGrid(
            'issues-grid',
            array('issues-grid[_filter][reporterName][value]' => 'John Doe')
        );

        $result = $this->getJsonResponseContent($response, 200);
        $result = reset($result['data']);

        $crawler = $this->client->request(
            'GET',
            $this->getUrl('oroacademic_issue_update', array('id' => $result['id']))
        );

        $form = $crawler->selectButton('Save and Close')->form();
        $form['oroacademic_issue[summary]'] = 'Task updated';
        $form['oroacademic_issue[description]'] = 'Description updated';

        $this->client->followRedirects(true);
        $crawler = $this->client->submit($form);

        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertContains("Issue Successfully Saved", $crawler->html());
    }

    /**
     * @depends testUpdate
     */
    public function testView()
    {
        $response = $this->client->requestGrid(
            'issues-grid',
            array('tasks-grid[_filter][reporterName][value]' => 'John Doe')
        );

        $result = $this->getJsonResponseContent($response, 200);
        $result = reset($result['data']);

        $this->client->request(
            'GET',
            $this->getUrl('oroacademic_issue_view', array('id' => $result['id']))
        );

        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $content = $result->getContent();
        $this->assertContains('Involved Users', $content);
        $this->assertContains('Activity', $content);
        $this->assertContains('Related Issues', $content);
    }

    /**
     * @depends testUpdate
     */
    public function testIndex()
    {
        $this->client->request('GET', $this->getUrl('oroacademic_issue_index'));
        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertContains('Issues', $result->getContent());
        $this->assertContains('Create Issue', $result->getContent());
        $this->assertContains('Export', $result->getContent());
        $this->assertContains('Import', $result->getContent());
    }
}
