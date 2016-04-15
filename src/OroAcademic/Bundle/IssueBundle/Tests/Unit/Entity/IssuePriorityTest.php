<?php

namespace OroAcademic\Bundle\IssueBundle\Tests\Unit\Entity;

use OroAcademic\Bundle\IssueBundle\Entity\IssuePriority;

class IssuePriorityTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        new IssuePriority('low');
    }

    /**
     * @dataProvider settersAndGettersDataProvider
     */
    public function testSettersAndGetters($property, $value)
    {
        $obj = new IssuePriority('low');

        call_user_func_array(array($obj, 'set' . ucfirst($property)), array($value));
        $this->assertEquals($value, call_user_func_array(array($obj, 'get' . ucfirst($property)), array()));
    }

    public function testGetName()
    {
        $expected = 'low';
        $entity = new IssuePriority($expected);
        $this->assertEquals($expected, $entity->getName());
    }

    public function testToString()
    {
        $expected = 'Low test';
        $entity = new IssuePriority('low');
        $entity->setLabel($expected);
        $this->assertEquals($expected, (string)$entity);
    }

    public function settersAndGettersDataProvider()
    {
        return array(
            array('label', 'Test LOW'),
            array('order', 1)
        );
    }
}
