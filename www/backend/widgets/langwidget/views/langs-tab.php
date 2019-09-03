<?php

use yii\helpers\ArrayHelper;
?>
<div class="language-tab-box">
    <?php if (count($languages) > 0): ?>
        <ul class="nav nav-tabs">
            <?php foreach ($languages as $k => $v): ?>
                <li class="<?php echo ($v['alias'] == 'ru') ? 'active' : NULL ?>">
                    <a href="<?php echo '#language_' . $v['alias']; ?>" class="<?php echo $v['alias']; ?>" data-toggle="tab" style="color: #949ba2"><?php echo $v['lang']; ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <div class="tab-content">
        <?php foreach ($languages as $k => $v0): ?>
            <div class="tab-pane fade <?php echo ($k == 0) ? 'in active' : NULL; ?>" id="<?php echo 'language_' . $v0['alias']; ?>">
                <?php foreach ($fields as $field): ?>
                    <?php $attribute = strtolower($class) . '-' . $field["name"] . '-' . $v0['alias']; ?>
                    <div class="form-group <?php echo 'field-' . strtolower($class) . '-' . $field["name"] . '-' . $v0['alias']; ?> required  <?php echo (isset($model->errors[$attribute])) ? 'has-error' : NULL; ?>">
                        <?php if ($field['type'] != 'hidden'): ?>
                            <label class="control-label" for="<?php echo strtolower($class) . '-' . $field["name"] . '-' . $v0['alias']; ?>"><?php echo $model->getAttributeLabel($field["name"]); ?></label>
                        <?php endif; ?>
                        <?php if ($field['type'] != 'widget'): ?>
                            <input
                                type="<?php echo $field["type"]; ?>"
                                id="<?php echo strtolower($class) . '-' . $field["name"] . '-' . $v0['alias']; ?>"
                                class="form-control" name="<?php echo $class . '[Language][' . $v0['alias'] . '][' . $field['name'] . ']'; ?>"
                                value="<?php echo (isset($model->languageData['Language'][$v0['alias']][$field['name']])) ? $model->languageData['Language'][$v0['alias']][$field['name']] : NULL; ?>">
                            <?php else : ?>
                                <?php
                                $widget_options = [
                                    'name' => $class . '[Language][' . $v0['alias'] . '][' . $field["name"] . ']',
                                    'value' => (isset($model->languageData['Language'][$v0['alias']][$field["name"]])) ? $model->languageData['Language'][$v0['alias']][$field["name"]] : NULL,
                                ];
                                if (isset($field['options'])) {
                                    $widget_options = ArrayHelper::merge($widget_options, $field['options']);
                                }
                                echo $field['class']::widget($widget_options);
                                ?>
                            <?php endif; ?>
                        <div class="help-block"><?php echo (isset($model->errors[$attribute][0])) ? $model->errors[$attribute][0] : NULL; ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
