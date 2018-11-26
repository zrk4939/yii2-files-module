<?php

use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $files \zrk4939\modules\files\models\File[] */
/* @var $searchModel \zrk4939\modules\files\models\FileSearch */
/* @var $pages \yii\data\Pagination */
/* @var $frame boolean */
/* @var $containerName string */

/* @var $CKEditor string */
/* @var $CKEditorFuncNum string */

/* @var $staticHost string */

$dataColumns = [
    ['class' => 'yii\grid\SerialColumn'],
    'preview:raw',
    'filename',
    'title',
];

if ($frame) {
    if (!empty($containerName)) {
        $script = <<<JS
$('.file-one-row').on('click', function() {
    window.opener.{$containerName}.addFilePreview($(this));
    
    if (!window.opener.{$containerName}.multiple){
        window.close();
    }
    
    return false;
});
JS;
    }

    if (!empty($CKEditor) && !empty($CKEditorFuncNum)) {
        $script = <<<JS
$('.file-one-row').on('click', function() {
    var data = $(this),
        path = data.data('file-url');
    
    window.opener.CKEDITOR.tools.callFunction( {$CKEditorFuncNum}, path);
    window.close();
});
JS;
    }

    if (!empty($script)) {
        $this->registerJs($script);
    }
}

?>

<div class="files-wrapper">
    <?php echo $this->render('_view-files', ['files' => $files, 'searchModel' => $searchModel, 'pages' => $pages, 'frame' => $frame, 'staticHost' => $staticHost]) ?>
</div>