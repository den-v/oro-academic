<?php

namespace OroAcademic\Bundle\IssueBundle\Tests\Unit\Form\Handler;

use OroAcademic\Bundle\IssueBundle\Form\Handler\IssueHandler;

class IssueHandlerTest extends AbstractHandlerTest
{
    public function setUp()
    {
        parent::setUp();
        $this->handler = new IssueHandler($this->form, $this->request, $this->manager);
    }
}
