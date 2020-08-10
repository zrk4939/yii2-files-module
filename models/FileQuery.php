<?php

namespace zrk4939\modules\files\models;

/**
 * This is the ActiveQuery class for [[File]].
 *
 * @see File
 */
class FileQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    public function mainImages()
    {
        return $this->andWhere(['parent_id' => null]);
    }

    /**
     * @param int $from
     * @param int $to
     * @return $this
     */
    public function filterUploadedRange($from = 0, $to = 999999999)
    {
        return $this->andFilterWhere(['between', 'created_at', $from, $to]);
    }

    /**
     * @inheritdoc
     * @return File[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return File|array|null
     */
    public function one($db = null)
    {
        $this->cache(60);
        return parent::one($db);
    }
}
