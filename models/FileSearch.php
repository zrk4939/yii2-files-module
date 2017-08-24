<?php

namespace zrk4939\modules\files\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * FileSearch represents the model behind the search form about `domain\modules\files\models\File`.
 */
class FileSearch extends File
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at'], 'integer'],
            [['path', 'filename', 'title'], 'safe'],
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

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @param array $where
     *
     * @return ActiveDataProvider
     */
    public function search($params, $where = [])
    {
        $query = File::find();

        if (!empty($where)){
            $query->andWhere($where);
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

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

        $query->andFilterWhere(['like', 'path', $this->path])
            ->andFilterWhere(['like', 'filename', $this->filename])
            ->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}
