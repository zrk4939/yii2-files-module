<?php

use domain\widgets\PluploadWidget\PluploadWidget;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \domain\modules\files\forms\FilesForm */
?>
<div class="file-create">

    <div class="file-form">

        <?php $form = ActiveForm::begin([
            'options' => ['data-pjax' => true]
        ]); ?>

        <?= $form->errorSummary($model) ?>

        <?= PluploadWidget::widget([
            'model' => $model,
            'attribute' => 'files_arr',
            'deleteAttribute' => 'files_del',
            'uploadUrl' => '/files/'
        ]) ?>

        <div class="form-group clearfix">
            <?= Html::submitButton(Yii::t('domain', 'Upload'), ['class' => 'btn btn-success pull-right']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
