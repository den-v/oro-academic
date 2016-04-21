<?php

namespace OroAcademic\Bundle\IssueBundle\Tests\Unit\Form\Type;

use OroAcademic\Bundle\IssueBundle\Form\Type\IssueType;

class IssueTypeTest extends AbstractTypeTest
{
       
    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();
        $this->expectedName = IssueType::NAME;
        $this->formType = new IssueType($this->typesProvider);
    }
}
