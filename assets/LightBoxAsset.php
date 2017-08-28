<?php
/**
 * Created by PhpStorm.
 * User: Илья
 * Date: 28.08.2017
 * Time: 11:42
 */

namespace zrk4939\modules\files\assets;


use yii\web\AssetBundle;

/**
 * Class LightBoxAsset
 * asset lightbox
 * @url http://lokeshdhakar.com/projects/lightbox2/
 */
class LightBoxAsset extends AssetBundle
{
    public $sourcePath = '@zrk4939/modules/files/assets/lightbox';

    public $css = [
        'css/lightbox.min.css',
    ];

    public $js = [
        'js/lightbox.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];
}
