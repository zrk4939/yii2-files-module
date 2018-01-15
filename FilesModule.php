<?php

namespace zrk4939\modules\files;

use yii\helpers\ArrayHelper;

/**
 * files module definition class
 */
class FilesModule extends \yii\base\Module
{
    public $extensions = ['png', 'jpg', 'jpeg', 'gif', 'pdf', 'txt'];
    public $tempDirectory = '@uploads/temp';
    public $rootAlias = '@webroot';

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

    public static function getTempDirectory()
    {
        return self::getInstance()->tempDirectory;
    }

    public static function getThumbs()
    {
        return self::getInstance()->thumbs;
    }

    public static function getRootAlias()
    {
        return self::getInstance()->rootAlias;
    }

    /**
     * @param string $key
     * @return array
     */
    public static function getPreviewSizes(string $key)
    {
        return ArrayHelper::getValue(self::getThumbs(), $key);
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
