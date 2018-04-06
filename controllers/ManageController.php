<?php

namespace zrk4939\modules\files\controllers;

use Yii;
use yii\bootstrap\Html;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use zrk4939\modules\files\actions\PluploadAction;
use zrk4939\modules\files\components\ImageOptimization;
use zrk4939\modules\files\FilesModule;
use zrk4939\modules\files\forms\FilesForm;
use zrk4939\modules\files\models\File;

/**
 * ManageController implements the CRUD actions for File model.
 */
class ManageController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        /** @var FilesModule $module */
        $module = $this->module;

        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => $module->accessRules
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'upload' => [
                'class' => PluploadAction::className(),
                'tempPath' => FilesModule::getTempDirectory(),
                'extensions' => FilesModule::getExtenstions(),
                'rename' => false,
                'onComplete' => function ($filename, $params) {
                    $response = [
                        'status' => 'ok',
                        'fileName' => $params['name'],
                        'preview' => $this->module->staticHost . '/uploads/temp/' . $params['name'],
                        'attr' => $params['attr'],
                        'inputName' => null,
                        'isImage' => ImageOptimization::isImage($filename)
                    ];
                    $response['inputName'] = Html::getInputName(new FilesForm(), 'files_arr[]');
                    return $response;
                }
            ],
        ];
    }

    /**
     * Lists all File models.
     * @param string|array $types
     * @param boolean $frame
     * @param string $containerName
     *
     * @return mixed
     */
    public function actionIndex($types = [], $frame = false, $containerName = null, $CKEditor = null, $CKEditorFuncNum = null)
    {
        $query = $this->getFilesQuery($types);

        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 50]);
        $files = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        $model = new FilesForm();

        if ($model->load(Yii::$app->request->post()) && $model->saveUploadFiles()) {
            return $this->redirect(['index', 'frame' => $frame, 'containerName' => $containerName]);
        }

        $renderParams = [
            'files' => $files,
            'pages' => $pages,
            'model' => $model,
            'frame' => $frame,
            'CKEditor' => $CKEditor,
            'CKEditorFuncNum' => $CKEditorFuncNum,
            'containerName' => $containerName,
            'staticHost' => $this->module->staticHost,
        ];

        if ($frame) {
            return $this->renderAjax('index', $renderParams);
        }

        return $this->render('index', $renderParams);
    }

    /**
     * Updates an existing File model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing File model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the File model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return File the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = File::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @param array|string $mimeTypes
     * @return \zrk4939\modules\files\models\FileQuery
     */
    private function getFilesQuery($mimeTypes = [])
    {
        $query = File::find()
            ->mainImages();

        if (!empty($mimeTypes)) {
            $types = Json::decode($mimeTypes);

            foreach ($types as $type) {
                $query->andFilterWhere(['like', 'mime', str_replace('*', '%', $type), false]);
            }
        }

        return $query;
    }
}
