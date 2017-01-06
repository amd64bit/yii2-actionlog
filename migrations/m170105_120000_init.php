<?php

class m161213_174900_init extends \atans\history\migrations\Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableName = \atans\history\models\History::tableName();

        $this->createTable($tableName, [
            'id'             => $this->primaryKey(),
            'user_id'          => $this->integer(11)->notNull(),
            'level'          => $this->string(50)->notNull(),
            'category' => $this->string(50)->notNull(),
            'message'            => $this->string(255)->notNull(),
            'data'           => $this->text()->notNull(),
            'ip'             => $this->string(42)->null(),
            'created_by'     => $this->string(42)->null(),
            'created_at'     => $this->dateTime()->notNull(),
        ], $this->tableOptions);

        $this->createIndex('user_id', $tableName, ['user_id']);
        $this->createIndex('level', $tableName, ['level']);
        $this->createIndex('category', $tableName, ['category']);
        $this->createIndex('key', $tableName, ['key']);
        $this->createIndex('created_by', $tableName, ['created_by']);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable(\atans\history\models\History::tableName());
    }
}