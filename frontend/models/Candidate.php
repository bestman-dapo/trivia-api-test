<?php

namespace frontend\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "candidate".
 *
 * @property int $id
 * @property string|null $candidate
 * @property string|null $category
 * @property string|null $time_start
 * @property string|null $time_ended
 * @property string|null $client
 * @property float|null $score
 */
class Candidate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'candidate';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            // [
            //     'class' => BlameableBehavior::class,

            // ]
            ];
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['time_start', 'time_ended'], 'safe'],
            [['score'], 'number'],
            [['candidate', 'category', 'client'], 'string', 'max' => 512],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'candidate' => 'Candidate',
            'category' => 'Category',
            'time_start' => 'Time Start',
            'time_ended' => 'Time Ended',
            'client' => 'Client',
            'score' => 'Score',
        ];
    }

    /**
     * {@inheritdoc}
     * @return CandidateQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CandidateQuery(get_called_class());
    }
}
