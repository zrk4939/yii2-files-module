<?php
/**
 * Created by PhpStorm.
 * User: Илья
 * Date: 11.08.2017
 * Time: 12:46
 */

namespace zrk4939\modules\files\forms;


use zrk4939\modules\files\FilesModule;
use zrk4939\modules\files\models\File;
use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;

class FilesForm extends Model
{
    public $files_arr;
    public $files_del;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['files_arr', 'files_del'], 'each', 'rule' => ['string']],
        ];
    }

    public function saveUploadFiles()
    {
        if (!$this->validate()) {
            return false;
        }

        $result = [
            'status' => 'ok',
            'errors' => [],
        ];

        if (!empty($this->files_arr)) {
            $tempDir = Yii::getAlias(FilesModule::getTempDirectory());

            foreach ($this->files_arr as $fileName) {
                $filePath = $tempDir . DIRECTORY_SEPARATOR . $fileName;

                if (file_exists($filePath) && is_file($filePath)) {
                    $info = pathinfo($filePath);

                    $root = Yii::getAlias(FilesModule::getRootAlias());

                    $model = new File();
                    $model->path = str_replace($root, '', $info['dirname']);
                    $model->filename = $info['basename'];
                    $model->status = 1;
                    $model->mime = FileHelper::getMimeType($filePath);
                    $model->filesize = filesize($filePath);

                    if (!$model->save()) {
                        $result['errors'][$fileName] = $model->getErrors();
                    }
                }
            }
        }

        return $result;
    }

}