<?php

namespace backend\modules\content\widgets\content_block_widget;

use yii\base\Widget;

class ContentBlockWidget extends Widget {

    public $type;
    public $block_id;
    public $value;
    public $name;
    public $label;
    public $content_id;
    public $group = 'block';
    public $errors = false;

    public function run() {
        return $this->render(
            'widget',
            [
                'block_id' => $this->block_id,
                'content_id' => $this->content_id,
                'value' => $this->value,
                'type' => $this->type,
                'name' => $this->name,
                'label' => $this->label,
                'group' => $this->group,
                'errors' => $this->errors,
            ]
        );
    }

}
