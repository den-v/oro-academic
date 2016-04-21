<?php

namespace OroAcademic\Bundle\IssueBundle\Tests\Unit\Form\Handler;

use OroAcademic\Bundle\IssueBundle\Form\Handler\IssueApiHandler;

class IssueApiHandlerTest extends AbstractHandlerTest
{
    public function setUp()
    {
        parent::setUp();
        $this->handler = new IssueApiHandler($this->form, $this->request, $this->manager);
    }
}
