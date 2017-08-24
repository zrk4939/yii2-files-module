<?php

namespace domain\modules\files;

/**
 * files module definition class
 */
class FilesModule extends \yii\base\Module
{
    public $extensions = ['png', 'jpg', 'jpeg', 'gif', 'pdf', 'txt'];
    public $tempDirectory = '/uploads/temp/';

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'domain\modules\files\controllers';

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

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
