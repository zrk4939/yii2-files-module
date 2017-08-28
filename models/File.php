<?php

namespace zrk4939\modules\files\models;

use zrk4939\modules\files\behaviors\UploadFilesBehavior;
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
 * @property boolean $isImage
 * @property string $preview
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
        // TODO add translations

        return [
            'id' => Yii::t('yii', 'ID'),
            'path' => Yii::t('yii', 'Path'),
            'filename' => Yii::t('yii', 'Filename'),
            'mime' => Yii::t('yii', 'Mime Type'),
            'title' => Yii::t('yii', 'Title'),
            'created_at' => Yii::t('yii', 'Created At'),
            'updated_at' => Yii::t('yii', 'Updated At'),
            'status' => Yii::t('yii', 'Active'),
        ];
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
            return Html::tag('span', Yii::t('yii', '(not available)'), ['class' => 'not-set']);
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
