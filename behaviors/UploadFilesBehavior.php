<?php
/**
 * Created by PhpStorm.
 * User: Илья
 * Date: 11.08.2017
 * Time: 13:44
 */

namespace zrk4939\modules\files\behaviors;


use zrk4939\modules\files\FilesModule;
use zrk4939\modules\files\models\File;
use Yii;
use yii\base\Behavior;
use yii\base\InvalidParamException;
use yii\base\ModelEvent;
use yii\db\ActiveRecord;

class UploadFilesBehavior extends Behavior
{
    public $attribute;
    public $moveTo = '@uploads/files';

    /**
     * @inheritdoc
     */
    public function init()
    {
        if (empty($this->attribute)) {
            throw new InvalidParamException("Param UploadFilesBehavior::attribute can not be empty.");
        }

        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'moveTempFile',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'moveTempFile',
            ActiveRecord::EVENT_BEFORE_DELETE => 'deleteFile',
        ];
    }

    /**
     * @param ModelEvent $event
     */
    public function moveTempFile(ModelEvent $event)
    {
        /* @var $model File */
        $model = $event->sender;

        $root = Yii::getAlias(FilesModule::getRootAlias());
        $uploadDir = Yii::getAlias($this->moveTo);
        $moveFrom = $root . $model->path;
        $moveTo = $uploadDir . DIRECTORY_SEPARATOR . strtotime(date("d.m.Y", time()));

        if (!is_dir($moveFrom)) {
            mkdir($moveFrom, 0775, true);
        }

        if (!is_dir($moveTo)) {
            mkdir($moveTo, 0775, true);
        }

        $fileFrom = $moveFrom . DIRECTORY_SEPARATOR . $model->filename;
        $fileTo = $moveTo . DIRECTORY_SEPARATOR . $model->filename;

        if (file_exists($fileFrom) && rename($fileFrom, $fileTo)) {
            $info = pathinfo($fileTo);

            $model->path = str_replace($root, '', $info['dirname']) . '/';
        }
    }

    /**
     * @param ModelEvent $event
     */
    public function deleteFile(ModelEvent $event)
    {
        /* @var $model File */
        $model = $event->sender;

        $deleteFile = Yii::getAlias('@uploads' . $model->path . $model->filename);

        if (file_exists($deleteFile) && is_file($deleteFile)) {
            unlink($deleteFile);
        }
    }
}