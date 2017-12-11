<?php

use yii\bootstrap\Html;

/* @var $this \yii\web\View */
/* @var $files \zrk4939\modules\files\models\File[] */
/* @var $inputName string */
/* @var $multiple boolean */


if (!empty($files)) {
    if ($multiple && is_array($files)) {
        foreach ($files as $file) {
            echo $this->render('_files-one', ['file' => $file, 'inputName' => $inputName]);
        }
    } elseif (!$multiple && is_object($files)) {
        echo $this->render('_files-one', ['file' => $files, 'inputName' => $inputName]);
    }
} else {
    echo Html::tag('span', Yii::t('yii', '(not set)'), ['class' => 'not-set']);
}

