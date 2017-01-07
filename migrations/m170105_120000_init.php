<?php

class m170105_120000_init extends \atans\actionlog\migrations\Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableName = \atans\actionlog\models\ActionLog::tableName();

        $this->createTable($tableName, [
            'id'         => $this->primaryKey(),
            'user_id'    => $this->integer(11)->notNull(),
            'level'      => $this->string(50)->notNull(),
            'category'   => $this->string(50)->notNull(),
            'message'    => $this->string(255)->notNull(),
            'data'       => $this->text()->null(),
            'ip'         => $this->string(42)->null(),
            'created_at' => $this->dateTime()->notNull(),
        ], $this->tableOptions);

        $this->createIndex('user_id', $tableName, ['user_id']);
        $this->createIndex('level', $tableName, ['level']);
        $this->createIndex('category', $tableName, ['category']);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable(\atans\actionlog\models\ActionLog::tableName());
    }
}