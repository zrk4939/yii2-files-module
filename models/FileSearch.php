<?php

namespace zrk4939\modules\files\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * FileSearch represents the model behind the search form about `zrk4939\modules\files\models\File`.
 */
class FileSearch extends File
{
    public $s_path; // Отдельные переменные, т.к. не должны перекликаться с оригинальными
    public $s_filename;

    public $uploaded_from;
    public $uploaded_to;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at'], 'integer'],
            [['s_path', 's_filename', 'title'], 'safe'],

            [['uploaded_from', 'uploaded_to'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            's_path' => Yii::t('files', 'Path'),
            's_filename' => Yii::t('files', 'Filename'),
            'uploaded_from' => Yii::t('files', 'Uploaded From'),
            'uploaded_to' => Yii::t('files', 'Uploaded To'),
        ]);
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @param array|string $mimeTypes
     *
     * @return \zrk4939\modules\files\models\FileQuery
     */
    public function search($params, $mimeTypes = [])
    {
        $query = $this->getFilesQuery($mimeTypes)
            ->orderBy(['created_at' => SORT_DESC]);

        // add conditions that should always apply here

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'path', $this->s_path])
            ->andFilterWhere(['like', 'filename', $this->s_filename])
            ->andFilterWhere(['like', 'title', $this->title])
            ->filterUploadedRange(strtotime($this->uploaded_from) ?: null, strtotime($this->uploaded_to) ?: null);

        return $query;
    }

    /**
     * @param array $params
     * @param array|string $mimeTypes
     * @return ActiveDataProvider
     */
    public function getDataProvider($params, $mimeTypes = [])
    {
        return new ActiveDataProvider([
            'query' => $this->search($params, $mimeTypes),
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ],
        ]);
    }

    /**
     * @param array|string $mimeTypes
     * @return \zrk4939\modules\files\models\FileQuery
     */
    protected function getFilesQuery($mimeTypes = [])
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
