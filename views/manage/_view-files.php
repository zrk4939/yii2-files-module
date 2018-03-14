<?php

use zrk4939\modules\files\assets\FilesAsset;
use zrk4939\helpers\ThumbnailHelper;
use zrk4939\modules\files\helpers\FilesHelper;
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $files \zrk4939\modules\files\models\File[] */
/* @var $pages \yii\data\Pagination */
/* @var $frame boolean */
/* @var $staticHost string */

FilesAsset::register($this);
?>
    <div class="">
        <?= $pages->totalCount ?> записей всего
    </div>

    <div class="files-wrapper">
        <?php
        // TODO ЭТО ВСЕ НУЖНО В ВИДЖЕТ
        foreach ($files as $file) {
            $textBlock = Html::tag('span', $file->filename, ['class' => 'files__filename']);
            $file_icon = FilesHelper::getFontAwesomeFileIcon($file);
            $file_icon = Html::tag('i', '', ['class' => $file_icon]);

            $template = "{$textBlock}\n{$file_icon}";
            if (!$frame) {
                $openLink = Html::a("<i class='glyphicon glyphicon-search btn btn-default'></i>", $file->fullPath, [
                    'title' => Yii::t('yii', 'View'),
                    'class' => 'image-open',
                    'data-lightbox' => 'image-' . $file->id,
                    'data-title' => $file->title,
                    'rel' => 'images images__image-link',
                ]);

                $editLink = Html::a("<i class='glyphicon glyphicon-edit btn btn-default'></i>", Url::to(['update', 'id' => $file->id]), [
                    'title' => Yii::t('yii', 'Update'),
                    'class' => 'file-edit',
                    'aria-label' => Yii::t('yii', 'Update'),
                    'data-pjax' => '0',
                ]);

                $deleteLink = Html::a("<i class='glyphicon glyphicon-trash btn btn-default'></i>", Url::to(['delete', 'id' => $file->id]), [
                    'title' => Yii::t('yii', 'Delete'),
                    'class' => 'file-delete',
                    'aria-label' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                    'data-method' => 'post',
                    'data-pjax' => '0',
                ]);

                $template = $file->isImage ? "{$textBlock}\n{$openLink}\n{$editLink}\n{$deleteLink}" : "{$textBlock}\n{$file_icon}\n{$editLink}\n{$deleteLink}";
            }

            $class = $file->isImage ? 'images__image' : 'images__file';

            // TODO background-image only for images files
            $options = [
                'data-file-id' => $file->id,
                'data-file-url' => $file->fullPath,
                'data-filename' => $file->filename,
                'data-is-image' => $file->isImage ? 1 : 0,
                'class' => $class . ' file-one-row',
                'style' => $file->isImage ? "background-image: url('{$file->fullPath}')" : null,

            ];

            echo Html::tag('div', $template, $options) . "\n";
        }
        ?>
    </div>

<?php
// display pagination
echo LinkPager::widget([
    'pagination' => $pages,
]);
?>