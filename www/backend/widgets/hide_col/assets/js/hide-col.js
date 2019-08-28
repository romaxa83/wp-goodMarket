$(document).ready(function () {
    console.log('HIDE_COL');
    var block = $('.check-list-hide-col');
    var model = block.data('model');
    var type = block.data('type');
    var user_id = block.data('user-id');

    // $('.check-hide-col').each(function(){
    //     if($(this)[0].checked){
    //         $('[data-attr = "'+ $(this).data('attr') +'"]').each(function(){
    //             $(this).hide();
    //         });
    //     }
    // });

    $('.check-hide-col').on('ifChecked', function () {
        var attr = $(this).data('attr');
        $('[data-attr = "' + attr + '"]').each(function () {
            $(this).hide();
        });

        $.ajax({
            url: host + '/admin/users/main/add-settings',
            type: 'post',
            data: {type: type, model: model, attr: attr, user_id: user_id}
        });
    });
    $('.check-hide-col').on('ifUnchecked', function () {
        var attr = $(this).data('attr');
        $('[data-attr = "' + attr + '"]').each(function () {
            $(this).show();
        });

        $.ajax({
            url: host + '/admin/users/main/remove-settings',
            type: 'post',
            data: {type: type, model: model, attr: attr, user_id: user_id}
        });
    });
});