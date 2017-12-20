<?php

namespace zrk4939\modules\files\models;

use yii\behaviors\TimestampBehavior;

class FileThumb extends File
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
}
