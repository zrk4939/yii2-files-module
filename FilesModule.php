<?php

namespace zrk4939\modules\files;

use yii\helpers\ArrayHelper;

/**
 * files module definition class
 *
 * @property string $rootAlias
 */
class FilesModule extends \yii\base\Module
{
    public $extensions = ['png', 'jpg', 'jpeg', 'gif', 'pdf', 'txt'];
    public $uploadPath = '@webroot/uploads';
    public $rootPath = '@webroot';

    public $staticHost = false;

    public $accessRules = [
        'main' => [
            'allow' => true,
            'roles' => ['@'],
        ],
    ];

    /**
     * @var array
     * [
     * 'preview' => [
     *      'width' => 250,
     *      'height' => 180,
     *      'cropAndCenter' => true
     * ],
     * ]
     */
    public $thumbs = [];

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'zrk4939\modules\files\controllers';

    /**
     * @return array
     */
    public static function getExtenstions()
    {
        return self::getInstance()->extensions;
    }

    public static function getUploadPath()
    {
        return self::getInstance()->uploadPath;
    }

    public static function getTempDirectory()
    {
        return self::getUploadPath() . '/temp';
    }

    public static function getThumbs()
    {
        return self::getInstance()->thumbs;
    }

    public static function getRootAlias()
    {
        return self::getInstance()->rootPath;
    }

    /**
     * @param string $key
     * @return array
     */
    public static function getPreviewSizes(string $key)
    {
        $default = [
            'width' => 150,
            'height' => 150,
            'cropAndCenter' => true,
            'force' => false,
            'quality' => 75
        ];
        $sizes = ArrayHelper::getValue(self::getThumbs(), $key, []);

        return ArrayHelper::merge($default, $sizes);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
