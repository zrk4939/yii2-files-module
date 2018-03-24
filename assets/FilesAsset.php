<?php
/**
 * Created by PhpStorm.
 * User: Илья
 * Date: 14.08.2017
 * Time: 16:14
 */

namespace zrk4939\modules\files\assets;


use yii\web\AssetBundle;

class FilesAsset extends AssetBundle
{
    public $sourcePath = '@zrk4939/modules/files/assets/dist';

    public $css = [
        'files.css',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'zrk4939\modules\files\assets\LightBoxAsset',
        'zrk4939\modules\files\assets\FontAwesomeAsset',
    ];
}
