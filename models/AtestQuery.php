<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Atest]].
 *
 * @see Atest
 */
class AtestQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Atest[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Atest|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}