<?php

namespace zrk4939\modules\files\models;

use zrk4939\modules\files\behaviors\UploadFilesBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\bootstrap\Html;
use yii\helpers\FileHelper;
use zrk4939\modules\files\FilesModule;
use zrk4939\modules\files\helpers\ThumbnailHelper;

/**
 * This is the model class for table "{{%file}}".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $path
 * @property string $filename
 * @property string $preview_key
 * @property string $title
 * @property string $mime
 * @property integer $filesize
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 *
 * @property File[] $previews
 *
 * @property string $fullPath
 * @property boolean $isImage
 * @property boolean isPreview
 */
class File extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%file}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['filename'], 'unique'],
            [['parent_id'], 'integer'],
            [['filesize'], 'integer'],
            [['path', 'filename', 'mime'], 'required'],
            [['path', 'filename', 'title', 'preview_key'], 'string', 'max' => 255],
            [['mime'], 'string', 'max' => 45],
            [['status'], 'boolean'],
            [['status'], 'default', 'value' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            [
                'class' => UploadFilesBehavior::className(),
                'attribute' => 'filename',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        // TODO add translations

        return [
            'id' => Yii::t('yii', 'ID'),
            'parent_id' => Yii::t('files', 'Parent'),
            'path' => Yii::t('files', 'Path'),
            'preview_key' => Yii::t('files', 'Prefix'),
            'filename' => Yii::t('files', 'Filename'),
            'mime' => Yii::t('files', 'Mime Type'),
            'title' => Yii::t('files', 'Title'),
            'created_at' => Yii::t('yii', 'Created At'),
            'updated_at' => Yii::t('yii', 'Updated At'),
            'status' => Yii::t('files', 'Active'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getPreviews()
    {
        return $this->hasMany(File::className(), ['parent_id' => 'id']);
    }

    /**
     * @return File
     */
    public function getPreview($key)
    {
        return $this->hasOne(File::className(), ['parent_id' => 'id'])->andWhere(['preview_key' => $key])->one();
    }

    /**
     * @return string
     */
    public function getFullPath()
    {
        return Yii::$app->params['frontendUrl'] . $this->path . $this->filename;
    }

    /**
     * @return boolean
     */
    public function getIsImage()
    {
        return (bool)preg_match('/^image/msiu', $this->mime);
    }

    /**
     * @return bool
     */
    public function getIsPreview()
    {
        return (bool)(!empty($this->parent_id));
    }

    /**
     * @inheritdoc
     * @return FileQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FileQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function beforeValidate()
    {
        $filePath = strrpos($this->path, '/', strlen($this->path) - 1)
            ? $this->path . $this->filename
            : $this->path . '/' . $this->filename;

        if (!file_exists(Yii::getAlias('@approot' . $filePath))) {
            $this->addError('path', 'Файл не найден!');
        }

        if (empty($this->mime)) {
            $this->mime = FileHelper::getMimeType(Yii::getAlias('@approot') . $filePath);
        }

        if (empty($this->filesize)) {
            $this->filesize = filesize(Yii::getAlias('@approot') . $filePath);
        }

        return parent::beforeValidate();
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        if (!$this->isPreview) {
            $this->generatePreviews();
        }

        parent::afterSave($insert, $changedAttributes);
    }

    private function generatePreviews()
    {
        $thumbs = FilesModule::getThumbs();
        foreach ($thumbs as $prefix => $sizes) {
            $uploadPath = Yii::getAlias('@approot' . $this->path);
            ThumbnailHelper::createImageThumbnails($uploadPath, $this->filename, $prefix, $sizes);

            $previewFilePath = $uploadPath . $prefix . '_' . $this->filename;

            if (file_exists($previewFilePath)) {
                $previewFile = new File();
                $previewFile->parent_id = $this->id;
                $previewFile->path = $this->path;
                $previewFile->filename = $prefix . '_' . $this->filename;
                $previewFile->preview_key = $prefix;
                $previewFile->status = 1;
                $previewFile->filesize = filesize($previewFilePath);
                $previewFile->mime = FileHelper::getMimeType($previewFilePath);

                $previewFile->save();
            }
        }
    }
}
