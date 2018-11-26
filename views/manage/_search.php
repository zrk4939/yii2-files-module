<?php

use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model zrk4939\modules\files\models\FileSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="file-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="col-xs-6 col-md-3">
            <?= $form->field($model, 's_filename') ?>
        </div>
        <div class="col-xs-6 col-md-3">
            <?= $form->field($model, 'title') ?>
        </div>
        <div class="col-xs-6 col-md-3">
            <?= $form->field($model, 'uploaded_from')->widget(DatePicker::class, [
                'options' => [
                    'class' => 'form-control'
                ],
            ]) ?>
        </div>
        <div class="col-xs-6 col-md-3">
            <?= $form->field($model, 'uploaded_to')->widget(DatePicker::class, [
                'options' => [
                    'class' => 'form-control'
                ]
            ]) ?>
        </div>
    </div>

    <div class="form-group clearfix">
        <div class="btn-group pull-right">
            <?= Html::submitButton(Yii::t('files', 'Search'), ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton(Yii::t('files', 'Reset'), ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
