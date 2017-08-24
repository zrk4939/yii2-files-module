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
            'path' => $this->string(255)->notNull(),
            'filename' => $this->string(255)->notNull()->unique(),
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
