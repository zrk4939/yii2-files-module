<?php
/**
 * Created by PhpStorm.
 * User: Илья
 * Date: 11.08.2017
 * Time: 15:41
 */

namespace zrk4939\modules\files\widget;


use yii\bootstrap\InputWidget;

class FilesWidget extends InputWidget
{
    public $multiple = false;
    public $files = [];
    public $types = [
        'image/*',
        'text/plain',
    ];

    public function run()
    {
        return $this->render('widget', [
            'model' => $this->model,
            'attribute' => $this->attribute,
            'multiple' => $this->multiple,
            'files' => $this->files,
            'types' => $this->types,
        ]);
    }
}