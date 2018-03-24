<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 24.03.2018
 * Time: 14:34
 */

namespace zrk4939\modules\files\assets;


use yii\web\AssetBundle;

class LightBoxAsset extends AssetBundle
{
    public $sourcePath = '@vendor/npm-asset/lightbox2/src';

    public $css = [
        'lightbox.js',
    ];

    public $js = [
        'lightbox.css',
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];
}
