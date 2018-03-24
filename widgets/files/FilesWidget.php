<?php
/**
 * Created by PhpStorm.
 * User: Илья
 * Date: 11.08.2017
 * Time: 15:41
 */

namespace zrk4939\modules\files\widgets\files;


use yii\bootstrap\InputWidget;
use zrk4939\modules\files\FilesModule;

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
        $mod = FilesModule::getInstance();

        return $this->render('widget', [
            'model' => $this->model,
            'attribute' => $this->attribute,
            'multiple' => $this->multiple,
            'files' => $this->files,
            'types' => $this->types,
            'staticHost' => $mod->staticHost,
        ]);
    }
}