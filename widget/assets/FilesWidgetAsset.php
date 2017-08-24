<?php
/**
 * Created by PhpStorm.
 * User: Илья
 * Date: 14.08.2017
 * Time: 10:53
 */

namespace domain\modules\files\widget\assets;


use yii\web\AssetBundle;

class FilesWidgetAsset extends AssetBundle
{
    public $sourcePath = '@domain/modules/files/widget/assets/dist';

    public $js = [
        'files-widget.js',
    ];

    public $css = [
        'files-widget.css',
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];
}