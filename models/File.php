<?php

namespace domain\modules\files\models;

use domain\modules\content\models\ContentItem;
use domain\modules\files\behaviors\UploadFilesBehavior;
use domain\modules\gallery\models\Gallery;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\bootstrap\Html;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "{{%file}}".
 *
 * @property integer $id
 * @property string $path
 * @property string $filename
 * @property string $title
 * @property string $mime
 * @property integer $filesize
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 *
 * @property ContentItem[] $contentItemsPreviews
 * @property ContentItem[] $contentItemsMainImages
 * @property Gallery[] $galleries
 *
 * @property boolean $isImage
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
            [['filename'], 'unique'],
            [['filesize'], 'integer'],
            [['path', 'filename', 'mime'], 'required'],
            [['path', 'filename', 'title'], 'string', 'max' => 255],
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
        return [
            'id' => Yii::t('domain', 'ID'),
            'path' => Yii::t('domain', 'Path'),
            'filename' => Yii::t('domain', 'Filename'),
            'mime' => Yii::t('domain', 'Mime Type'),
            'title' => Yii::t('domain', 'Title'),
            'created_at' => Yii::t('domain', 'Created At'),
            'updated_at' => Yii::t('domain', 'Updated At'),
            'status' => Yii::t('domain', 'Active'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContentItemsPreviews()
    {
        return $this->hasMany(ContentItem::className(), ['preview_image' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContentItemsMainImages()
    {
        return $this->hasMany(ContentItem::className(), ['main_image' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGalleries()
    {
        return $this->hasMany(Gallery::className(), ['id' => 'gallery_id'])->viaTable('{{%gallery_images}}', ['file_id' => 'id']);
    }

    /**
     * @return boolean
     */
    public function getIsImage()
    {
        return (boolean)preg_match('/^image/msiu', $this->mime);
    }

    /**
     * @return string
     */
    public function getPreview()
    {
        if (!$this->isImage) {
            return Html::tag('span', Yii::t('domain', '(not available)'), ['class' => 'not-set']);
        }

        $url = '/uploads' . $this->path . $this->filename;

        return Html::a(
            Html::img($url, ['alt' => $this->title, 'width' => 100, 'height' => 100]),
            $url,
            [
                'title' => $this->title,
                'class' => 'fancybox',
            ]
        );
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
        if (empty($this->mime)) {
            $this->mime = FileHelper::getMimeType(Yii::getAlias('@approot') . $this->path . $this->filename);
        }

        return parent::beforeValidate();
    }
}
