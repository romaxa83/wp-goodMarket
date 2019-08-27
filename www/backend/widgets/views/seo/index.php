<?php

use vova07\imperavi\Widget;

?>

<div class="box box-solid">
    <div class="box-header with-border">
        <h3 class="box-title"><b>SEO</b></h3>
    </div>
    <div class="box-body">
        <?php if (count($languages) > 0): ?>
            <ul class="nav nav-tabs">
                <?php foreach ($languages as $k => $v): ?>
                    <li class="<?php echo ($k == 'ru') ? 'active' : NULL ?>">
                        <a href="<?php echo '#' . $k . '_seo'; ?>" class="<?php echo $k; ?>" data-toggle="tab" style="color: #949ba2"><?php echo $v; ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <div class="tab-content">
            <?php foreach ($languages as $k0 => $v0): ?>
                <div class="tab-pane fade <?php echo ($k0 == 'ru') ? 'in active' : NULL ?>" id="<?php echo $k0 . '_seo'; ?>">
                    <?php foreach ($fields as $k1 => $v1): ?>
                        <?php if ($v1['type'] == 'text'): ?>
                            <div class="form-group">
                                <label class="control-label"><?php echo $v1['label']; ?></label>
                                <input type="<?php echo $v1['type']; ?>" class="form-control" name="<?php echo 'SEO[' . $k0 . '][' . $v1['name'] . ']'; ?>" value="<?php echo (isset($seo_data[$k0][$v1['name']])) ? $seo_data[$k0][$v1['name']] : NULL; ?>">
                            </div>
                        <?php endif; ?>
                        <?php if ($v1['type'] == 'textarea'): ?>
                            <div class="form-group">
                                <label for="comment"><?php echo $v1['label']; ?></label>
                                <textarea class="form-control" name="<?php echo 'SEO[' . $k0 . '][' . $v1['name'] . ']'; ?>" rows="5"><?php echo (isset($seo_data[$k0][$v1['name']])) ? $seo_data[$k0][$v1['name']] : NULL; ?></textarea>
                            </div>
                        <?php endif; ?>
                        <?php if ($v1['type'] == 'widget'): ?>
                            <?php
                            echo Widget::widget([
                                'name' => 'SEO[' . $k0 . '][' . $v1['name'] . ']',
                                'value' => (isset($seo_data[$k0][$v1['name']])) ? $seo_data[$k0][$v1['name']] : NULL,
                                'settings' => [
                                    'lang' => 'ru',
                                    'minHeight' => 200,
                                    'plugins' => [
                                        'clips',
                                        'fullscreen',
                                    ],
                                ],
                            ]);
                            ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
