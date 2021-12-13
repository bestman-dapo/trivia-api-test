<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%score}}`.
 */
class m211210_170053_create_score_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%score}}', [
            'id' => $this->primaryKey(),
            'user' => $this->string(length: 512),
            'score' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer()
        ]);

        $this->addForeignKey(name: 'FK_score_user_created_by', table:'{{%score}}', columns: 'created_by', refTable: '{{%user}}', refColumns: 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(name: 'FK_score_user_created_by', table:'{{%score}}');
        $this->dropTable('{{%score}}');
    }
}
