<?php

namespace OroAcademic\Bundle\IssueBundle\Tests\Unit\DependencyInjection;

use Oro\Bundle\TestFrameworkBundle\Test\DependencyInjection\ExtensionTestCase;
use OroAcademic\Bundle\IssueBundle\DependencyInjection\OroAcademicIssueExtension;

class OroAcademicIssueExtensionTest extends ExtensionTestCase
{
    public function testLoad()
    {
        $this->loadExtension(new OroAcademicIssueExtension());

        $expectedParameters = [
            //services.yml
            'oroacademic_issue.entity.class',
            'oroacademic_issue.note.listener.class',
            'oroacademic_issue.entity.listener.class',
            'oroacademic_issue.types.provider.class',
            'oroacademic_issue.types',
            // form.yml
            'oroacademic_issue.form.type.issue.class',
            'oroacademic_issue.form.type.issue_api.class',
            'oroacademic_issue.form.handler.issue.class',
            'oroacademic_issue.form.handler.issue_api.class',
            'oroacademic_issue.manager.api.class',
            // importexport.yml
            'issue.importexport.class',
        ];
        $this->assertParametersLoaded($expectedParameters);
        $this->assertEquals(count($expectedParameters), count($this->actualParameters));

        $expectedDefinitions = [
            // services.yml
            'oroacademic_issue.types.provider',
            'oroacademic_issue.note.listener',
            'oroacademic_issue.entity.listener',
            // form.yml
            'oroacademic_issue.form.type.issue',
            'oroacademic_issue.form.type.issue_api',
            'oroacademic_issue.form',
            'oroacademic_issue.form.api',
            'oroacademic_issue.form.handler.issue',
            'oroacademic_issue.form.handler.issue_api',
            'oroacademic_issue.manager.api',
            // importexport.yml
            'orocacademic.importexport.template_fixture.issue',
            'orocacademic.importexport.template_fixture.data_converter.issue',
            'orocacademic.importexport.data_converter.issue',
            'orocacademic.importexport.processor.export_template.issue',
            'orocacademic.importexport.processor.export.issue',
            'orocacademic.importexport.strategy.issue.add_or_replace',
            'orocacademic.importexport.processor.import.issue',
        ];
        $this->assertDefinitionsLoaded($expectedDefinitions);
        $this->assertEquals(count($expectedDefinitions), count($this->actualDefinitions));
    }
}
