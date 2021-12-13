<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[score]].
 *
 * @see score
 */
class ScoreQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return score[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return score|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
