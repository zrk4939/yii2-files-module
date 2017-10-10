<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use zrk4939\modules\files\helpers\FilesHelper;

/* @var $this yii\web\View */
/* @var $model zrk4939\modules\files\models\File */

$this->title = Yii::t('files', 'Updating');
$this->params['breadcrumbs'][] = ['label' => Yii::t('files', 'Files'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title
?>
<div class="file-update">

    <div class="file-form">

        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-xs-12 col-md-5">
                <div class="form-group">
                    <?= ($model->isImage) ? Html::img($model->path . $model->filename) : null ?>

                    <?php if (preg_match('/video/msiu', $model->mime)): ?>
                        <video loop controls tabindex="0">
                            <source src="<?= $model->path . $model->filename ?>"
                                    type='<?= $model->mime ?>; codecs="<?= FilesHelper::getVideoCodecs($model->mime) ?>"'/>
                        </video>
                    <?php endif; ?>

                </div>
            </div>
            <div class="col-xs-12 col-md-7">
                <?= $form->field($model, 'path')->textInput(['readonly' => true]) ?>

                <?= $form->field($model, 'filename')->textInput(['readonly' => true]) ?>

                <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'status')->checkbox() ?>

                <div class="form-group clearfix">
                    <?= Html::submitButton(Yii::t('yii', 'Save'), ['class' => 'btn btn-success pull-right']) ?>
                </div>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
