<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model domain\modules\files\models\FileSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="file-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="col-xs-12 col-md-6">
            <?= $form->field($model, 'filename') ?>
        </div>
        <div class="col-xs-12 col-md-6">
            <?= $form->field($model, 'title') ?>
        </div>
    </div>

    <div class="form-group clearfix">
        <div class="btn-group pull-right">
            <?= Html::submitButton(Yii::t('domain', 'Search'), ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton(Yii::t('domain', 'Reset'), ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
