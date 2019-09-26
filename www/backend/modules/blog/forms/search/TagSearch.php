<?php

namespace backend\modules\blog\forms\search;

use backend\modules\blog\entities\Tag;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class TagSearch extends Model
{
    public $id;
    public $title;
    public $alias;
    public $status;

    public function rules(): array
    {
        return [
            [['id'], 'integer'],
            [['alias', 'title','status'], 'safe'],
        ];
    }
    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Tag::find()->with('oneLang');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_ASC]
            ]
        ]);
        $this->load($params);
        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere([
            'id' => $this->id,
        ]);
        $query
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'alias', $this->alias])
            ->andFilterWhere(['like', 'status', $this->status]);
        return $dataProvider;
    }
}