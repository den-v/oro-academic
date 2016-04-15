<?php

namespace OroAcademic\Bundle\IssueBundle\Tests\Unit\Form\Type;

use Symfony\Component\Form\Test\FormIntegrationTestCase;
use OroAcademic\Bundle\IssueBundle\Form\Type\IssueType;

class IssueTypeTest extends FormIntegrationTestCase
{
       
    /**
     * @var  IssueType
     */
    protected $formType;

    /**
     * @var  array
     */
    protected $issueTypes = ['Bug' => 'Bug', 'Test' => 'Test', 'Story' => 'Story'];

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();
        $typesProvider = $this
            ->getMockBuilder('OroAcademic\Bundle\IssueBundle\Provider\IssueTypesProvider')
            ->disableOriginalConstructor()
            ->getMock();
        $typesProvider
            ->expects($this->once())
            ->method('getIssueTypes')
            //->with(['type' => 'main'])
            ->willReturn($this->issueTypes);
        $this->formType = new IssueType($typesProvider);
    }

    /**
     * @param array $widgets
     *
     * @dataProvider formTypeProvider
     */
    public function testBuildForm(array $widgets)
    {
        $builder = $this->getMockBuilder('Symfony\Component\Form\FormBuilder')
            ->disableOriginalConstructor()
            ->getMock();

        $builder->expects($this->exactly(7))
            ->method('add')
            ->will($this->returnSelf());

        foreach ($widgets as $key => $widget) {
            $builder->expects($this->at($key))
                ->method('add')
                ->with($this->equalTo($widget))
                ->will($this->returnSelf());
        }

        $this->formType->buildForm($builder, []);
    }

    public function formTypeProvider()
    {
        return [
            'all' => [
                'widgets' => [
                    'code',
                    'summary',
                    'type',
                    'description',
                    'priority',
                    'assignee',
                    'relatedIssues',
                ]
            ]
        ];
    }

    public function testGetName()
    {
        $this->assertEquals(IssueType::NAME, $this->formType->getName());
    }
}
