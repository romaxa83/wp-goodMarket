<?php

namespace backend\modules\blog\forms;

use backend\modules\blog\entities\Meta;
use yii\base\Model;

class MetaForm extends Model
{
    public $h1;
    public $title;
    public $keywords;
    public $description;
    public $seo_text;

    public function __construct(Meta $meta = null, $config = [])
    {
        if ($meta) {
            $this->h1 = $meta->h1;
            $this->title = $meta->title;
            $this->keywords = $meta->keywords;
            $this->description = $meta->description;
            $this->seo_text = $meta->seo_text;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['title','h1',], 'string', 'max' => 255],
            [['description', 'keywords','seo_text'], 'string'],
        ];
    }
}
