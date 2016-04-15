<?php

namespace OroAcademic\Bundle\IssueBundle\Tests\Functional\Controller\Dashboard;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

/**
 * Class DashboardControllerTest
 * @package OroAcademic\Bundle\IssueBundle\Tests\Functional\Controller\Dashboard
 *
 * @dbIsolation
 */
class DashboardControllerTest extends WebTestCase
{

    public function setUp()
    {
        $this->initClient();
    }

    public function testIssuesByStatusAction()
    {
        $this->client->request(
            'GET',
            $this->getUrl(
                'oroacademic_dashboard_issues_by_state_chart',
                ['widget' => 'issues_by_state']
            )
        );
        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertContains('Issues by Status', $result->getContent());
    }

    public function testIssueWidgetGridAction()
    {
        $this->client->request(
            'GET',
            $this->getUrl(
                'oroacademic_dashboard_issues_widget_grid',
                ['widget' => 'issues_widget']
            )
        );
        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertContains('Resent Active Issues', $result->getContent());
    }
}
