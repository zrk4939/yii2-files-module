<?php

use yii\db\Migration;

/**
 * Handles the creation of table `file`.
 */
class m170816_074308_create_file_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('file', [
            'id' => $this->primaryKey(),
            'parent_id' => $this->integer(),
            'path' => $this->string(255)->notNull(),
            'preview_key' => $this->string(255),
            'filename' => $this->string(255)->notNull()->unique(),
            'filesize' => $this->integer()->notNull(),
            'title' => $this->string(255),
            'mime' => $this->string()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'status' => $this->smallInteger()->defaultValue(0),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('file');
    }
}
