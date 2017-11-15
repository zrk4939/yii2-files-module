<?php

namespace zrk4939\modules\files;

/**
 * files module definition class
 */
class FilesModule extends \yii\base\Module
{
    public $extensions = ['png', 'jpg', 'jpeg', 'gif', 'pdf', 'txt'];
    public $tempDirectory = '/uploads/temp/';

    public $thumbs = [
        'preview' => [
            'width' => 250,
            'height' => 180,
            'cropAndCenter' => true
        ],
    ];

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

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
