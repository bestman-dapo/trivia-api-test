<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%candiddate}}`.
 */
class m211212_114046_add_quizid_column_to_candiddate_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%candidate}}', 'quizid', $this->string(length: 512));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
