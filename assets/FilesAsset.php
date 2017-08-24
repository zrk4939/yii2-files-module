<?php
/**
 * Created by PhpStorm.
 * User: Илья
 * Date: 14.08.2017
 * Time: 16:14
 */

namespace domain\modules\files\assets;


use yii\web\AssetBundle;

class FilesAsset extends AssetBundle
{
    public $sourcePath = '@domain/modules/files/assets/dist';

    public $css = [
        'files.css',
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];
}
