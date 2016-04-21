<?php

namespace OroAcademic\Bundle\IssueBundle\Tests\Unit\Form\Type;

use OroAcademic\Bundle\IssueBundle\Form\Type\IssueApiType;

class IssueApiTypeTest extends IssueTypeTest
{

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
            ->willReturn($this->issueTypes);
        $this->formType = new IssueApiType($typesProvider);
    }

    public function testGetName()
    {
        $this->assertEquals(IssueApiType::NAME, $this->formType->getName());
    }

    protected function assertFormOptions($form)
    {
        parent::assertFormOptions($form);
        $formConfig = $form->getConfig();
        $this->assertEquals(false, $formConfig->getOption('csrf_protection'));
    }
}
