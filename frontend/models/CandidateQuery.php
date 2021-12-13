<?php

namespace frontend\models;

/**
 * This is the ActiveQuery class for [[Candidate]].
 *
 * @see Candidate
 */
class CandidateQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Candidate[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Candidate|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
