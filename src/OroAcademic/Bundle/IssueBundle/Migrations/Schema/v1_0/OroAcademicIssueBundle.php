<?php
/**
 * Created by PhpStorm.
 * User: vdenchyk
 * Date: 30/03/16
 * Time: 19:59
 */

namespace OroAcademic\Bundle\IssueBundle\Migrations\Schema\v1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\EntityExtendBundle\Migration\OroOptions;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class OroAcademicIssueBundle implements Migration
{
    /**
     * {@inheritdoc}
     */
    public function getMigrationVersion()
    {
        return 'v1_0';
    }
    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        self::addIssueTable($schema);
    }
    /**
     * {@inheritdoc}
     */
    public static function addIssueTable(Schema $schema)
    {
        $options = new OroOptions();

        $table = $schema->createTable('oroacademic_issue_priority');
        $table->addColumn('name', 'string', ['length' => 32, 'notnull' => false]);
        $table->addColumn('label', 'string', ['length' => 255, 'notnull' => false]);
        $table->addColumn('order', 'integer', ['notnull' => false]);
        $table->setPrimaryKey(['name']);

        $table = $schema->createTable('oroacademic_issue_resolution');
        $table->addColumn('name', 'string', ['length' => 32, 'notnull' => false]);
        $table->addColumn('label', 'string', ['length' => 255, 'notnull' => false]);
        $table->setPrimaryKey(['name']);

        $table = $schema->createTable('oroacademic_issue');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('code', 'string', ['length' => 255]);
        $table->addColumn('summary', 'string', ['length' => 255]);
        $table->addColumn('description', 'text', ['notnull' => false]);
        $table->addColumn('type', 'string', ['length' => 255]);
        $table->addColumn('priority', 'integer', ['notnull' => false]);
        $table->addColumn('resolution', 'integer', ['notnull' => false]);
        $table->addColumn('organization_id', 'integer', ['notnull' => false]);
        $table->addColumn('reporter_id', 'integer', ['notnull' => false]);
        $table->addColumn('assignee_id', 'integer', ['notnull' => false]);
        $table->addColumn('parent_id', 'integer', ['notnull' => false]);
        $table->addColumn('workflow_item_id', 'integer', ['notnull' => false]);
        $table->addColumn('workflow_step_id', 'integer', ['notnull' => false]);
        $table->addColumn('createdAt', 'datetime', []);
        $table->addColumn('updatedAt', 'datetime', []);
        $table->addUniqueIndex(['code'], 'UNIQ_ISSUE_CODE');
        $table->addUniqueIndex(['workflow_item_id'], 'UNIQ_ISSUE_WF_ITEM');
        $table->setPrimaryKey(['id']);
        // Hide tags column from grid by default.
        $options->set('tag', 'enableGridColumn', false);
        // Hide tags filter from grid by default.
        $options->set('tag', 'enableGridFilter', false);
        $table->addOption(OroOptions::KEY, $options);

        $table = $schema->createTable('oroacademic_issue_collaborator');
        $table->addColumn('issue_id', 'integer', []);
        $table->addColumn('user_id', 'integer', []);
        $table->setPrimaryKey(['issue_id', 'user_id']);
        $table->addIndex(['issue_id'], 'IDX_COLLAB_ISSUE', []);
        $table->addIndex(['user_id'], 'IDX_COLLAB_USER', []);

        $table = $schema->createTable('oroacademic_issue_related');
        $table->addColumn('issue_source', 'integer', []);
        $table->addColumn('issue_target', 'integer', []);
        $table->setPrimaryKey(['issue_source', 'issue_target']);
        $table->addIndex(['issue_source'], 'IDX_REL_SRC_ISSUE', []);
        $table->addIndex(['issue_target'], 'IDX_REL_TRG_ISSUE', []);
    }
}