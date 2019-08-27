$(document).ready(function () {
    console.log('USER');
    $('.password_eye').on('click', function () {
        if ($(this).next().attr('type') == 'password') {
            $(this).next().attr('type', 'text');
            $(this).children('i').attr('class', '');
            $(this).children('i').addClass('fa fa-eye-slash');
        } else {
            $(this).next().attr('type', 'password');
            $(this).children('i').attr('class', '');
            $(this).children('i').addClass('fa fa-eye');
        }
    });

    $('.all_delete_user').on('click', function (e) {
        e.preventDefault();
        var item = {};
        var check = $('input[name="delete[]"]:checked');
        $.each(check, function (index, value) {
            item[index] = $(value).val();
        });
        $.post( host + "/admin/users/people/people-list/all-delete", item)
            .done(function(data) {
                window.location.reload()
            });
    });

    $(function () {
        
        //Money Euro
        $('[data-mask]').inputmask();
    });

    $('.show-product-order').click(function(){
        // $('.product-row').each(function(){
        //     $(this).addClass('hide');
        // });
        var order_id = $(this).data('order-id');
        var row = $('[data-product-row-order = "'+order_id+'"]');
        if(row.hasClass('hide')){
            $('.product-row').each(function(){
                $(this).addClass('hide');
            });
            row.removeClass('hide');

        } else {
            row.addClass('hide');
        }
    });

});