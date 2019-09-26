<div class="item <?=($type=='answer')?'review-answer':''?>" data-id="<?=$model['id']?>">
    <p class="review-name"><?php echo ($model['user_id'] != 0) ? $model['first_name'].' '.$model['last_name'] : 'Гость'?></p>
    <p><?=$model['text']?></p>
    <?php if($type=='review'):?>
         <div class="review-rating">
            <input type="hidden"
                   id="rating_<?=$model['id']?>"
                   name="rating_<?=$model['id']?>"
                   value="<?=$model['rating']?>"
                   data-filled-star='<span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>'
                   data-empty-star='<span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>'
                   data-display-only="true"
                   data-show-caption="false"
                   data-size="1.13em">
        </div>
    <?php endif?>
    <script>
       $(document).ready(function () {
            $("#rating_<?=$model['id']?>").rating();
        });
    </script>
    <div class="additional-info">
        <span class="date"><?= date('Y-m-d H:i:s', strtotime($model['date'])) ?></span>
        <?php if($type=='review' && isset($user) && $user->type == 0):?>
            <a class="no-btn answer">Ответить</a>
        <?php endif?>
    </div>
</div>
<?php if (isset($model['answers']) && count($model['answers']) > 0):?>
    <?php foreach ($model['answers'] as $answer):?>
        <div class="item review-answer" data-id="<?=$answer['id']?>">
            <p class="review-name"><?php echo ($answer['user_id'] != 0) ? $answer['first_name'].' '.$answer['last_name'] : 'Гость'; ?></p>
            <p><?= $answer['text']?></p>
            <div class="additional-info">
                <span class="date"><?= date('Y-m-d H:i:s', strtotime($answer['date'])) ?></span>
            </div>
        </div>
    <?php endforeach;?>
<?php endif;?>
