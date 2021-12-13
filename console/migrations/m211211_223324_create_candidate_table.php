<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%candidate}}`.
 */
class m211211_223324_create_candidate_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%candidate}}', [
            'id' => $this->primaryKey(),
            'candidate' => $this->string(length: 512),
            'category' => $this->string(length: 512),
            'time_start' => $this->date("Y-m-d H:i:s"),
            'time_ended' => $this->date("Y-m-d H:i:s"),
            'client' => $this->string(length: 512),
            'score' => $this->float(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%candidate}}');
    }
}
