<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%candidate}}`.
 */
class m211212_112620_add_duration_column_to_candidate_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('{{%candidate}}', 'duration', $this->float());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
