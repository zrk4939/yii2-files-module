<?php

use yii\bootstrap\Html;

/* @var $this \yii\web\View */
/* @var $files \domain\modules\files\models\File[] */
/* @var $inputName string */
/* @var $multiple boolean */


if (!empty($files)) {
    foreach ($files as $file) {
        $class = $file->isImage ? 'preview-file image' : 'preview-file';
        $input = Html::input('hidden', $inputName, $file->id);
        $cancel = Html::a("", '#', ['class' => 'glyphicon glyphicon-remove cancel']);
        $text = $file->isImage ? '' : $file->filename;

        echo Html::tag('div', "{$text}\n{$cancel}\n{$input}", [
            'class' => $class,
            'style' => "background-image:url('{$file->path}{$file->filename}')"
        ]) . "\n";
    }
} else {
    echo Html::tag('span', Yii::t('yii', '(not set)'), ['class' => 'not-set']);
}

