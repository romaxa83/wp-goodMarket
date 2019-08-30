<?php
namespace backend\modules\blog\forms\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\blog\entities\Category;

class CategorySearch extends Model
{
    public $id;
    public $title;
    public $alias;
    public $parent_id;

    public function rules(): array
    {
        return [
            [['id','parent_id'], 'integer'],
            [['title', 'alias'], 'safe'],
        ];
    }
    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Category::find()->andWhere(['>', 'depth', 0])->andWhere(['not',['id' => 1]]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['id','lft','title','alias','status'],
                'defaultOrder' => ['lft' => SORT_ASC]
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
            ->andFilterWhere(['like', 'parent_id', $this->parent_id]);
        return $dataProvider;
    }
}
