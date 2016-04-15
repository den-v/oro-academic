<?php
/**
 * Created by PhpStorm.
 * User: vdenchyk
 * Date: 14.04.16
 * Time: 23:32
 */

namespace OroAcademic\Bundle\IssueBundle\Tests\Unit\Provider;

use OroAcademic\Bundle\IssueBundle\Provider\IssueTypesProvider;

class IssueTypesProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var IssueTypesProvider
     */
    protected $provider;

    /**
     * @var  array
     */
    protected $issueTypes;

    public function setUp()
    {
        $this->issueTypes = [
            ['name' => 'Bug', 'type' => 'main'],
            ['name' => 'Task', 'type' => 'main'],
            ['name' => 'Subtask', 'type' => 'sub'],
        ];
        $this->provider = new IssueTypesProvider($this->issueTypes);
    }

    public function tearDown()
    {
        unset($this->registry);
    }

    public function testGetIssueTypes()
    {
        $this->assertEquals(1, count($this->provider->getIssueTypes('sub')));
        $this->assertEquals(2, count($this->provider->getIssueTypes('main')));
        $this->assertEquals(3, count($this->provider->getIssueTypes('all')));
    }
}
