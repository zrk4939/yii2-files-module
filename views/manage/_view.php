<?php

use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $files \domain\modules\files\models\File[] */
/* @var $pages \yii\data\Pagination */
/* @var $frame boolean */
/* @var $containerName string */

$dataColumns = [
    ['class' => 'yii\grid\SerialColumn'],
    'preview:raw',
    'filename',
    'title',
];

if ($frame) {
    $script = <<<JS
$('.file-one-row').on('click', function() {
    window.opener.{$containerName}.addFilePreview($(this));
    
    if (!window.opener.{$containerName}.multiple){
        window.close();
    }
    
    return false;
});
JS;
    $this->registerJs($script);
} else {
    $dataColumns[] = ['class' => 'domain\helpers\ActionColumn'];
}

?>
<?php Pjax::begin([
    'enablePushState' => false,
    'id' => 'grid-files-pjax'
]) ?>
    <div class="files-wrapper">
        <?php echo $this->render('_view-files', ['files' => $files, 'pages' => $pages, 'frame' => $frame]) ?>
    </div>
<?php Pjax::end() ?>