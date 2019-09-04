<?php

namespace backend\modules\blog\forms;

use yii\base\Model;
use backend\modules\blog\entities\Tag;
use backend\modules\blog\validators\AliasValidator;

class TagForm extends Model
{
    public $title;
    public $alias;

    private $_tag;

    //если попадает тег заполняем его данными,иначе ичего не делаем
    public function __construct(Tag $tag = null,array $config = [])
    {
        if($tag){
            $this->title = $tag->title;
            $this->alias = $tag->alias;
            $this->_tag = $tag;
        }
        parent::__construct($config);
    }

    //правила вилидации
    public function rules() {
        return [
            [['alias', 'title'], 'required'],
            [['alias', 'title'], 'string','max' => 250],
            ['alias', AliasValidator::class],
            [['title', 'alias'], 'unique', 'targetClass' => Tag::class, 'filter' => $this->_tag ? ['<>', 'id', $this->_tag->id] : null],

        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название тега',
            'alias' => 'Алиас тега',
            'status' => 'Статус',
        ];
    }
}
