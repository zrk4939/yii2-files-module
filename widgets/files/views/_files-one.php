<?php

use yii\bootstrap\Html;

/* @var $this \yii\web\View */
/* @var $file \zrk4939\modules\files\models\File */
/* @var $inputName string */
/* @var $staticHost string */

$class = $file->isImage ? 'preview-file image' : 'preview-file';
$input = Html::input('hidden', $inputName, $file->id);
$cancel = Html::a("", '#', ['class' => 'glyphicon glyphicon-remove cancel']);
$text = $file->isImage ? '' : $file->filename;

// TODO background-image only for images files
echo Html::tag('div', "{$text}\n{$cancel}\n{$input}", [
        'class' => $class,
        'style' => "background-image:url('{$file->fullPath}')"
    ]) . "\n";