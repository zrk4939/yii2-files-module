<?php

namespace zrk4939\modules\files\models;

use Yii;
use yii\base\InvalidArgumentException;
use yii\behaviors\TimestampBehavior;
use yii\helpers\FileHelper;
use zrk4939\modules\files\behaviors\FileNameBehavior;
use zrk4939\modules\files\behaviors\UploadFilesBehavior;
use zrk4939\modules\files\components\ThumbnailHelper;
use zrk4939\modules\files\FilesModule;

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
                'moveTo' => FilesModule::getUploadPath() . '/files',
            ],
            [
                'class' => FileNameBehavior::className(),
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
     * @return yii\db\ActiveQuery
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
        $preview = $this->generatePreview($key);

        return $preview;
    }

    /**
     * @return string
     */
    public function getFullPath()
    {
        $mod = FilesModule::getInstance();

        $urlPath = strrpos($this->path, '/', strlen($this->path) - 1)
            ? $this->path . $this->filename
            : $this->path . '/' . $this->filename;

        return $mod->staticHost ? $mod->staticHost . $urlPath : $urlPath;
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
     * TODO BEFORE INSERT/UPDATE
     */
    public function beforeValidate()
    {
        $this->path = strrpos($this->path, '/', strlen($this->path) - 1)
            ? $this->path
            : $this->path . '/';

        $path = Yii::getAlias(FilesModule::getRootAlias()) . $this->path . $this->filename;

        if (!file_exists($path)) {
            $this->addError('path', 'Файл не найден!');
        }

        if (empty($this->mime)) {
            $this->mime = FileHelper::getMimeType($path);
        }

        if (empty($this->filesize)) {
            $this->filesize = filesize($path);
        }

        return parent::beforeValidate();
    }

    public function delete()
    {
        if ($this->deletePreviews()) {
            $transaction = Yii::$app->db->beginTransaction();

            if (parent::delete() && $this->deleteFile()) {
                $transaction->commit();
                return true;
            }
        }


        $transaction->rollBack();
        return false;
    }

    private function deleteFile()
    {
        $path = Yii::getAlias(FilesModule::getRootAlias()) . $this->path . $this->filename;
        if (file_exists($path)) {
            return unlink($path);
        }

        return true;
    }

    /**
     * @return bool
     * @throws \Throwable
     */
    private function deletePreviews()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($this->previews as $previewFile) {
                $previewFile->delete();
            }

            $transaction->commit();
            return true;
        } catch (\Exception $exception) {
            Yii::error($exception->getMessage());
            $transaction->rollBack();
            return false;
        }
    }

    /**
     * @param string $key
     * @return File
     * @throws \Exception
     */
    private function generatePreview(string $key)
    {
        $previewFile = $this->hasOne(File::className(), ['parent_id' => 'id'])->andWhere(['preview_key' => $key])->one();

        $sizes = FilesModule::getPreviewSizes($key);
        if (empty($sizes)) {
            throw new InvalidArgumentException("Preview key settings not found for key «{$key}»");
        }

        $file_path = Yii::getAlias(FilesModule::getRootAlias() . $this->path);
        ThumbnailHelper::createImageThumbnail($file_path, $this->filename, $key, $sizes);

        $thumbFileName = $key . '_' . $this->filename;
        $previewFilePath = $file_path . $thumbFileName;

        if (!($previewFile) && file_exists($previewFilePath)) {
            $previewFile = new FileThumb();
            $previewFile->parent_id = $this->id;
            $previewFile->path = $this->path;
            $previewFile->filename = $thumbFileName;
            $previewFile->preview_key = $key;
            $previewFile->status = 1;
            $previewFile->filesize = filesize($previewFilePath);
            $previewFile->mime = FileHelper::getMimeType($previewFilePath);

            $previewFile->save();
        } elseif (!file_exists($previewFilePath)) {
            return $this;
        }

        return $previewFile;
    }
}
