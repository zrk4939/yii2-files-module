<?php

use yii\bootstrap\Tabs;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $files \zrk4939\modules\files\models\File[] */
/* @var $pages \yii\data\Pagination */
/* @var $model \zrk4939\modules\files\forms\FilesForm */
/* @var $frame boolean */
/* @var $containerName string */

$this->title = Yii::t('domain', 'Files');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="file-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= Tabs::widget([
        'items' => [
            [
                'label' => Yii::t('domain', 'View'),
                'content' => $this->render('_view', [
                    'files' => $files,
                    'pages' => $pages,
                    'frame' => $frame,
                    'containerName' => $containerName
                ]),
            ],
            [
                'label' => Yii::t('domain', 'Upload'),
                'content' => $this->render('_uploading', ['model' => $model]),
            ]
        ]
    ])
    ?>
</div>
