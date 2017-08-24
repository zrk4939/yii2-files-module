<?php

use kartik\switchinput\SwitchInput;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model zrk4939\modules\files\models\File */

$this->title = Yii::t('domain', 'Updating');
$this->params['breadcrumbs'][] = ['label' => Yii::t('domain', 'Files'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title
?>
<div class="file-update">

    <div class="file-form">

        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-xs-12 col-md-4">
                <div class="form-group">
                    <?= Html::img($model->path . $model->filename) ?>
                </div>
            </div>
            <div class="col-xs-12 col-md-8">
                <?= $form->field($model, 'path')->textInput(['readonly' => true]) ?>

                <?= $form->field($model, 'filename')->textInput(['readonly' => true]) ?>

                <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'status')->widget(SwitchInput::className()) ?>

                <div class="form-group clearfix">
                    <?= Html::submitButton(Yii::t('domain', 'Save'), ['class' => 'btn btn-success pull-right']) ?>
                </div>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
