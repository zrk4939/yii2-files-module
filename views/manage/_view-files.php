<?php

use domain\helpers\ThumbnailHelper;
use domain\modules\files\helpers\FilesHelper;
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $files \domain\modules\files\models\File[] */
/* @var $pages \yii\data\Pagination */
/* @var $frame boolean */

\domain\modules\files\assets\FilesAsset::register($this);
?>
<div class="">
    <?= $pages->totalCount ?> записей всего
</div>

    <div class="files-wrapper">
        <?php
        // TODO ЭТО ВСЕ НУЖНО В ВИДЖЕТ
        foreach ($files as $file) {
            $url = $file->path . $file->filename;
            $previewUrl = $file->path . ThumbnailHelper::getImageThumbNail(Yii::getAlias('@approot' . $file->path), $file->filename);

            $textBlock = Html::tag('span', $file->filename, ['class' => 'files__filename']);
            $file_icon = FilesHelper::getFontAwesomeFileIcon($file);
            $file_icon = Html::tag('i', '', ['class' => $file_icon]);

            $template = "{$textBlock}\n{$file_icon}";
            if (!$frame) {
                $openLink = Html::a("<i class='glyphicon glyphicon-search btn btn-default'></i>", $url, [
                    'title' => Yii::t('domain', 'View'),
                    'class' => 'image-open fancybox',
                    'rel' => 'images images__image-link',
                ]);

                $editLink = Html::a("<i class='glyphicon glyphicon-edit btn btn-default'></i>", Url::to(['update', 'id' => $file->id]), [
                    'title' => Yii::t('domain', 'Update'),
                    'class' => 'file-edit',
                    'aria-label' => Yii::t('yii', 'Update'),
                    'data-pjax' => '0',
                ]);

                $deleteLink = Html::a("<i class='glyphicon glyphicon-trash btn btn-default'></i>", Url::to(['delete', 'id' => $file->id]), [
                    'title' => Yii::t('domain', 'Delete'),
                    'class' => 'file-delete',
                    'aria-label' => Yii::t('domain', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                    'data-method' => 'post',
                    'data-pjax' => '0',
                ]);

                $template = $file->isImage ? "{$textBlock}\n{$openLink}\n{$editLink}\n{$deleteLink}" : "{$textBlock}\n{$file_icon}\n{$editLink}\n{$deleteLink}";
            }

            $class = $file->isImage ? 'images__image' : 'images__file';
            $options = [
                'data-file-id' => $file->id,
                'data-file-path' => $file->path,
                'data-filename' => $file->filename,
                'data-is-image' => $file->isImage ? 1 : 0,
                'class' => $class . ' file-one-row',
                'style' => $file->isImage ? "background-image: url('{$previewUrl}')" : null,

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