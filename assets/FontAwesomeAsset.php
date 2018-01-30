<?php
/**
 * Created by PhpStorm.
 * User: zrk4939
 * Date: 30.01.2018
 * Time: 10:42
 */

namespace zrk4939\modules\files\assets;


use yii\web\AssetBundle;

class FontAwesomeAsset extends AssetBundle
{
    public $sourcePath = '@vendor/fortawesome/font-awesome';
    public $css = [
        'css/font-awesome.min.css'
    ];
    public $js = [

    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
