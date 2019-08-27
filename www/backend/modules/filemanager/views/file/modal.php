<?php

use yii\helpers\Html;

?>
    <div role="filemanager-modal" class="modal" tabindex="-1"
         data-frame-id="<?= $frameId ?>"
         data-frame-src="<?= $frameSrc ?>"
         data-btn-id="<?= $btnId ?>"
         data-input-id="<?= $inputId ?>"
         data-image-container="<?= isset($imageContainer) ? $imageContainer : '' ?>"
         data-paste-data="<?= isset($pasteData) ? $pasteData : '' ?>"
         data-thumb="<?= $thumb ?>" data-tag="<?= $defaultTag ?>">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body"></div>
        </div>
    </div>
</div>
<?php
$script = <<< JS
    $('body').on('load', '#product-media_id-frame', function() {
        $(this).contents().find('#filemanager-tagIds').val("$defaultTag");
    })
JS;
$this->registerJs($script);