<?php

namespace OroAcademic\Bundle\IssueBundle\Migrations\Schema\v1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

/**
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 */
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
        /** Tables generation **/
        $this->createOroacademicIssueTable($schema);
        $this->createOroacademicIssueCollaboratorTable($schema);
        $this->createOroacademicIssuePriorityTable($schema);
        $this->createOroacademicIssueRelatedTable($schema);
        $this->createOroacademicIssueResolutionTable($schema);

        /** Foreign keys generation **/
        $this->addOroacademicIssueForeignKeys($schema);
    }

    /**
     * Create oroacademic_issue table
     *
     * @param Schema $schema
     */
    protected function createOroacademicIssueTable(Schema $schema)
    {
        $table = $schema->createTable('oroacademic_issue');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('workflow_item_id', 'integer', ['notnull' => false]);
        $table->addColumn('workflow_step_id', 'integer', ['notnull' => false]);
        $table->addColumn('parent_id', 'integer', ['notnull' => false]);
        $table->addColumn('assignee_id', 'integer', ['notnull' => false]);
        $table->addColumn('reporter_id', 'integer', ['notnull' => false]);
        $table->addColumn('organization_id', 'integer', ['notnull' => false]);
        $table->addColumn('priority', 'string', ['notnull' => false, 'length' => 32]);
        $table->addColumn('resolution', 'string', ['notnull' => false, 'length' => 32]);
        $table->addColumn('summary', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('code', 'string', ['length' => 32]);
        $table->addColumn('description', 'text', ['notnull' => false]);
        $table->addColumn('type', 'string', ['length' => 32]);
        $table->addColumn('createdAt', 'datetime', []);
        $table->addColumn('updatedAt', 'datetime', []);
        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['code'], 'UNIQ_5A6B25E77153098');
        $table->addUniqueIndex(['workflow_item_id'], 'UNIQ_5A6B25E1023C4EE');
        $table->addIndex(['priority'], 'fk_priority_idx', []);
        $table->addIndex(['resolution'], 'fk_resolution_idx', []);
        $table->addIndex(['organization_id'], 'fk_organization_idx', []);
        $table->addIndex(['assignee_id'], 'fk_assignee_idx', []);
        $table->addIndex(['reporter_id'], 'fk_reporter_idx', []);
        $table->addIndex(['parent_id'], 'IDX_5A6B25E727ACA70', []);
        $table->addIndex(['workflow_step_id'], 'IDX_5A6B25E71FE882C', []);
        $table->addIndex(['updatedAt'], 'issue_updated_at_idx', []);
    }

    /**
     * Create oroacademic_issue_collaborator table
     *
     * @param Schema $schema
     */
    protected function createOroacademicIssueCollaboratorTable(Schema $schema)
    {
        $table = $schema->createTable('oroacademic_issue_collaborator');
        $table->addColumn('issue_id', 'integer', []);
        $table->addColumn('user_id', 'integer', []);
        $table->setPrimaryKey(['issue_id', 'user_id']);
        $table->addIndex(['issue_id'], 'IDX_COLLAB_ISSUE', []);
        $table->addIndex(['user_id'], 'IDX_COLLAB_USER', []);
    }

    /**
     * Create oroacademic_issue_priority table
     *
     * @param Schema $schema
     */
    protected function createOroacademicIssuePriorityTable(Schema $schema)
    {
        $table = $schema->createTable('oroacademic_issue_priority');
        $table->addColumn('name', 'string', ['length' => 32]);
        $table->addColumn('label', 'string', ['length' => 255]);
        $table->addColumn('order', 'integer', []);
        $table->setPrimaryKey(['name']);
        $table->addUniqueIndex(['label'], 'PRT_LBL_IDX');
    }

    /**
     * Create oroacademic_issue_related table
     *
     * @param Schema $schema
     */
    protected function createOroacademicIssueRelatedTable(Schema $schema)
    {
        $table = $schema->createTable('oroacademic_issue_related');
        $table->addColumn('issue_source', 'integer', []);
        $table->addColumn('issue_target', 'integer', []);
        $table->setPrimaryKey(['issue_source', 'issue_target']);
    }

    /**
     * Create oroacademic_issue_resolution table
     *
     * @param Schema $schema
     */
    protected function createOroacademicIssueResolutionTable(Schema $schema)
    {
        $table = $schema->createTable('oroacademic_issue_resolution');
        $table->addColumn('name', 'string', ['length' => 32]);
        $table->addColumn('label', 'string', ['length' => 255]);
        $table->setPrimaryKey(['name']);
        $table->addUniqueIndex(['label'], 'RSL_LBL_IDX');
    }

    /**
     * Add oroacademic_issue foreign keys.
     *
     * @param Schema $schema
     */
    protected function addOroacademicIssueForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('oroacademic_issue');
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_workflow_item'),
            ['workflow_item_id'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_workflow_step'),
            ['workflow_step_id'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oroacademic_issue'),
            ['parent_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_user'),
            ['assignee_id'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_user'),
            ['reporter_id'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_organization'),
            ['organization_id'],
            ['id'],
            ['onDelete' => 'NO ACTION', 'onUpdate' => 'NO ACTION']
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oroacademic_issue_priority'),
            ['priority'],
            ['name'],
            ['onDelete' => 'NO ACTION', 'onUpdate' => 'NO ACTION']
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_user'),
            ['reporter_id'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => 'SET NULL']
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oroacademic_issue_resolution'),
            ['resolution'],
            ['name'],
            ['onDelete' => 'NO ACTION', 'onUpdate' => 'NO ACTION']
        );
    }
}
