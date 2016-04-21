<?php

namespace OroAcademic\Bundle\IssueBundle\Tests\Unit\Form\Type;

use OroAcademic\Bundle\IssueBundle\Form\Type\IssueApiType;

class IssueApiTypeTest extends AbstractTypeTest
{

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();
        $this->expectedName = IssueApiType::NAME;
        $this->formType = new IssueApiType($this->typesProvider);
    }
    
    /**
     * {@inheritdoc}
     */
    protected function assertFormOptions($form)
    {
        parent::assertFormOptions($form);
        $formConfig = $form->getConfig();
        $this->assertEquals(false, $formConfig->getOption('csrf_protection'));
    }
}
