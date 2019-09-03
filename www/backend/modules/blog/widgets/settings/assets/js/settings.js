$(document).ready(function(){
    console.log('USER_SETTINGS');
    var block = $('.settings-for-user');
    var model = block.data('model');
    var type = $('.check-list-hide-col').data('type');
    var user_id = block.data('user-id');


    $('.check-hide-col').on('change',function(){
        var attr = $(this).data('attr-hide');

        if(this.checked){
            $('[data-attr = "'+ attr +'"]').each(function(){
                $(this).hide();
            });
            $.ajax({
                url:host + '/admin/site/add-settings',
                type:'post',
                data:{type:type,model:model,attr:attr,user_id:user_id}
            });
        } else {
            $('[data-attr = "'+ attr +'"]').each(function(){
                $(this).show();
            });
            $.ajax({
                url:host + '/admin/site/remove-settings',
                type:'post',
                data:{type:type,model:model,attr:attr,user_id:user_id}
            });
        }
    });
    $('.control-page').on('blur',function(){
        var type = $(this).data('type');
        var attr = $(this).val();
        var max = $(this).attr('max');
        var min = $(this).attr('min');
        var xhr = true;
        if(parseInt(attr) > parseInt(max) || parseInt(attr) < parseInt(min)){
            xhr = false;
            $(this).val($(this).data('old-value'));
        }
        if(xhr){
            $.ajax({
                url:host + '/admin/site/add-settings',
                type:'post',
                data:{type:type,model:model,attr:attr,user_id:user_id},
                success:function(){
                    location.reload();
                }
            });
        }

    });
    // Для iCheck
    $('.check-hide-col').on('ifChecked',function(){
        var attr = $(this).data('attr');
        $('[data-attr = "'+ attr +'"]').each(function(){
            $(this).hide();
        });

        $.ajax({
            url:host + '/admin/site/add-settings',
            type:'post',
            data:{type:type,model:model,attr:attr,user_id:user_id}
        });
    });
    $('.check-hide-col').on('ifUnchecked',function(){
        var attr = $(this).data('attr');
        $('[data-attr = "'+ attr +'"]').each(function(){
            $(this).show();
        });

        $.ajax({
            url:host + '/admin/site/remove-settings',
            type:'post',
            data:{type:type,model:model,attr:attr,user_id:user_id}
        });
    });
});
