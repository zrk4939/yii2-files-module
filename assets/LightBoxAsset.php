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
    public $sourcePath = '@vendor/npm-asset/lightbox2/dist';

    public $css = [
        'css/lightbox.min.css',
    ];

    public $js = [
        'js/lightbox.min.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];
}
