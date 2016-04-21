<?php

namespace OroAcademic\Bundle\IssueBundle\Tests\Unit\Form\Type;

use Oro\Bundle\UserBundle\Entity\User;
use OroAcademic\Bundle\IssueBundle\Tests\Unit\Form\Type\Stub\EntityType as StubEntityType;
use Symfony\Component\Form\Test\FormIntegrationTestCase;
use OroAcademic\Bundle\IssueBundle\Form\Type\IssueType;
use OroAcademic\Bundle\IssueBundle\Provider\IssueTypesProvider;
use Symfony\Component\Form\PreloadedExtension;

abstract class AbstractTypeTest extends FormIntegrationTestCase
{
       
    /**
     * @var  IssueType
     */
    protected $formType = null;

    /**
     * @var  array
     */
    protected $issueTypes = ['Bug' => 'Bug', 'Test' => 'Test', 'Story' => 'Story'];

    /**
     * @var  \PHPUnit_Framework_MockObject_MockObject|IssueTypesProvider
     */
    protected $typesProvider;

    /**
     * @var  \PHPUnit_Framework_MockObject_MockObject|User
     */
    public $assignee;

    /**
     * @var  string|null
     */
    public $expectedName = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();
        $this->typesProvider = $this
            ->getMockBuilder('OroAcademic\Bundle\IssueBundle\Provider\IssueTypesProvider')
            ->disableOriginalConstructor()
            ->getMock();
        $this->typesProvider
            ->expects($this->once())
            ->method('getIssueTypes')
            ->willReturn($this->issueTypes);
        $this->assignee = $this->getEntity('Oro\Bundle\UserBundle\Entity\User', 1);
    }

    protected function getExtensions()
    {
        $entityType = new StubEntityType(
            [
                1 => $this->getEntity('OroAcademic\Bundle\IssueBundle\Entity\IssuePriority', 'low', 'name'),
                2 => $this->getEntity('OroAcademic\Bundle\IssueBundle\Entity\IssuePriority', 'high', 'name'),
            ],
            'entity'
        );

        $userSelectType = new StubEntityType(
            [
                1 => $this->getEntity('Oro\Bundle\UserBundle\Entity\User', 1),
                2 => $this->getEntity('Oro\Bundle\UserBundle\Entity\User', 2),
            ],
            'oro_user_select'
        );

        $translEntityType = new StubEntityType(
            [
                1 => $this->getEntity('OroAcademic\Bundle\IssueBundle\Entity\Issue', 1),
                2 => $this->getEntity('OroAcademic\Bundle\IssueBundle\Entity\Issue', 2),
            ],
            'translatable_entity'
        );


        return array(
            new PreloadedExtension(
                [
                    'entity' =>$entityType,
                    'oro_user_select' => $userSelectType,
                    'translatable_entity' => $translEntityType
                ],
                []
            ),
        );
    }

    /**
     * @dataProvider submitDataProvider
     * @param mixed $defaultData
     * @param array $submittedData
     * @param array $expectedData
     */
    public function testSubmit(
        $defaultData,
        $submittedData,
        $expectedData
    ) {
        $expectedData['assignee'] = $this->assignee;
        $form = $this->factory->create($this->formType);

        $this->assertFormOptions($form);

        $this->assertEquals($defaultData, $form->getData());

        $form->submit($submittedData);

        $this->assertTrue($form->isValid());
        foreach ($expectedData as $field => $data) {
            $this->assertTrue($form->has($field));
            $fieldForm = $form->get($field);
            $fieldData = $fieldForm->getData();

            if (is_object($data) && is_object($fieldData)) {
                $this->assertObject($data, $fieldData);
            } else {
                $this->assertEquals($data, $fieldData);
            }
        }
    }

    /**
     * @return array
     */
    public function submitDataProvider()
    {
        $code = 'BB-1111';
        $summary = 'summary';
        $description = 'description';
        $type = 'Bug';
        $relatedIssues = [
            0 => $this->getEntity('OroAcademic\Bundle\IssueBundle\Entity\Issue', 1),
            1 => $this->getEntity('OroAcademic\Bundle\IssueBundle\Entity\Issue', 2),
        ];

        return [
            'valid' => [
                'defaultData' => null,
                'submittedData' => [
                    'code' => $code,
                    'summary' => $summary,
                    'description' => $description,
                    'type' => $type,
                    'assignee' => 1,
                    'relatedIssues' => [1,2],
                ],
                'expectedData' => [
                    'code' => $code,
                    'summary' => $summary,
                    'description' => $description,
                    'type' => $type,
                    'assignee' => $this->assignee,
                    'relatedIssues' => $relatedIssues,
                ],
            ]
        ];
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
        $this->assertEquals($this->expectedName, $this->formType->getName());
    }

    /**
     * @param string $className
     * @param int|string $idValue
     * @param string $idProperty
     * @return object
     */
    protected function getEntity($className, $idValue, $idProperty = 'id')
    {
        $entity = new $className;

        $reflectionClass = new \ReflectionClass($className);
        $method = $reflectionClass->getProperty($idProperty);
        $method->setAccessible(true);
        $method->setValue($entity, $idValue);

        return $entity;
    }

    protected function assertObject($expected, $actual)
    {
        $this->assertInstanceOf(get_class($expected), $actual);
        $this->assertEquals(get_object_vars($expected), get_object_vars($actual));
        $this->assertEquals($expected->getId(), $actual->getId());
    }

    protected function assertFormOptions($form)
    {
        $formConfig = $form->getConfig();
        $this->assertEquals('OroAcademic\Bundle\IssueBundle\Entity\Issue', $formConfig->getOption('data_class'));
    }

    public function tearDown()
    {
        parent::tearDown();
        unset($this->assignee);
    }
}

