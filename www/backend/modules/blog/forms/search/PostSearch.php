<?php

namespace backend\modules\blog\forms\search;

use backend\modules\blog\entities\Category;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use backend\modules\blog\entities\Post;

class PostSearch extends Model
{
    public $id;
    public $title;
    public $alias;
    public $status;
    public $category_id;
    public $is_main;
    public $created_at;

    public function rules(): array
    {
        return [
            [['id', 'status','is_main'], 'integer'],
            [['title','alias','created_at','category_id'], 'safe'],
        ];
    }

    public function exportFields()
    {
        return [
            'category_id' => function($model){
                $category = Category::find()->select('title')->where(['id' => $model->category_id])->one();
                return $category->title;
            },
        ];
    }
    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Post::find();

        $query->joinWith(['category']);
        $query->joinWith(['langRow']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC]
            ],
            'pagination' => [
                'pageSize' => 10,
            ]
        ]);

        $this->load($params);
        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query
            ->andFilterWhere(['=', 'blog_posts.id', $this->id])
            ->andFilterWhere(['like', 'blog_posts.title', $this->title])
            ->andFilterWhere(['like', 'blog_posts.alias', $this->alias])
            ->andFilterWhere(['=', 'blog_posts.status', $this->status])
            ->andFilterWhere(['=', 'blog_posts.is_main', $this->is_main])
            ->andFilterWhere(['like', 'blog_categories.title', $this->category_id]);

        $query->andFilterWhere(['>=', 'blog_posts.created_at', $this->created_at ? strtotime($this->created_at . ' 00:00:00') : null]);
        $query->andFilterWhere(['<=', 'blog_posts.created_at', $this->created_at ? strtotime($this->created_at . ' 23:59:59') : null]);

        return $dataProvider;
    }
}
