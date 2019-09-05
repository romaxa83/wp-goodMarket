$(document).ready(function(){

    $('#delivery').on('selectmenuchange',function(e,u){
        $('.courier-delivery').hide();
        $('.np-delivery').hide();
        var val = u.item.value;
        if(val == 2){
            $('.courier-delivery').show();
        }
        if(val == 1){
            $('.np-delivery').show();
        }
    });

    var textFields = [];
    textFields.push(
        $('#select2-ui-id-2-container .select2-selection__placeholder'),
        $('#select2-ui-id-2-container .select2-selection__placeholder').parents('.select2-selection'),
        $('#ui-id-4-button'),
        $('#delivery-button'),
        $('.street.form-control').parents('.form-group'),
        $('.house.form-control').parents('.form-group'),
        $('.flat.form-control').parents('.form-group')
    );

    $(textFields).each(function (index, element) {
        $(element).on('click', function () {
            var ind = $(this).index();
            $(element).eq(ind).removeAttr('style');
        });
    });

    $("#thanxmodal").on("hidden.bs.modal", function () {
        var url = $(this).data('href');
        window.location.replace(url);
    });

    $('.send-order').on('click',function(){
        var xhr = true;
        var user_status = $('.user-data').data('user-status');
        var city = user_status == 'user' ? $('#select2-ui-id-2-container').text():$('#select2-ui-id-1-container').text();
        var office = user_status == 'user' ? $('#select2-ui-id-3-container').text():$('#select2-ui-id-2-container').text();
        var delivery = $('.delivery').val();
        var payment = $('.payment').val();
        var comment = $('.comment').val();
        var house = $('.house').val();
        var street = $('.street').val();
        var flat = $('.flat').val();
        var user_id = $('.user-data').data('user-id');
        var phone = $('#orderPhone').val();
        var validates = [];

        if(city == '' || city == '*Введите ваш город') {
            $('#ui-id-2,#ui-id-1').next('span').css({
                'border':'1px solid #ff0000'
            });
            $('#ui-id-2,#ui-id-1').parent().find('.help-block').text('Выберите ваш город');
            validates['city'] = 'error';
        }else{
            $('#ui-id-2,#ui-id-1').next('span').css({
                'border':''
            });
            $('#ui-id-2,#ui-id-1').parent().find('.help-block').text('');
            validates['city'] = 'success';
        }
        if(phone.replace(/\+|-|\(|\)| |_/g,'').length != 12){
            $('#orderPhone').css({
                'border':'1px solid #ff0000'
            });
            $('#orderPhone').next('div').text('Введите корректно номер телефона');
            validates['phone'] = 'error';
        }else{
            $('#orderPhone').css({
                'border':''
            });
            $('#orderPhone').next('div').text('');
            validates['phone'] = 'success';
        }
        if(payment == 0){
            $('#ui-id-4-button,#ui-id-3-button').css({
                'border':'1px solid #ff0000'
            });
            $('#ui-id-4-button,#ui-id-3-button').next('div').text('Выберите способ оплаты');
            validates['payment'] = 'error';
        }else{
            $('#ui-id-4-button,#ui-id-3-button').css({
                'border':''
            });
            $('#ui-id-4-button,#ui-id-3-button').next('div').text('');
            validates['payment'] = 'success';
        }
        if(delivery == 0){
            $('#delivery-button').css({
                'border':'1px solid #ff0000'
            });
            $('#delivery-button').next('div').text('Выберите способ доставки');
            validates['delivery'] = 'error';
        }else if(delivery == 1){
            $('#delivery-button').css({
                'border':''
            });
            $('#delivery-button').next('div').text('');
            validates['delivery'] = 'success';
        }
        if(delivery == 2){
            $('#delivery-button').css({
                'border':''
            });
            $('#delivery-button').next('div').text('');
            validates['delivery'] = 'success';

            if(street == ''){
                $('.street.form-control').css({
                    'border': '1px solid #ff0000'
                });
                $('.street.form-control').next('div').text('Укажите улицу');
                validates['street'] = 'error';
            }else{
                $('.street.form-control').css({
                    'border': ''
                });
                $('.street.form-control').next('div').text('');
                validates['street'] = 'success';
            }
            if(house == ''){
                $('.house.form-control').css({
                    'border': '1px solid #ff0000'
                });
                $('.house.form-control').next('div').text('Укажите номер дома');
                validates['house'] = 'error';
            }else{
                $('.house.form-control').css({
                    'border': ''
                });
                $('.house.form-control').next('div').text('');
                validates['house'] = 'success';
            }
            // if(flat == ''){
            //     $('.flat.form-control').css({
            //         'border': '1px solid #ff0000'
            //     });
            //     $('.flat.form-control').next('div').text('Укажите номер квартиры');
            //     errors['flat'] = 'error';
            // }else{
            //     $('.flat.form-control').css({
            //         'border': ''
            //     });
            //     $('.flat.form-control').next('div').text('');
            //     errors['flat'] = 'success';
            // }
        }
        var data = {
            city:city,
            office:office,
            delivery:delivery,
            payment:payment,
            comment:comment,
            house:house,
            street:street,
            flat:flat,
            user_id:user_id,
            user_status:user_status,
            phone : phone
        };
        if(user_status == 'guest'){
            var cart = [];
            $('.wrap-cart').find('.remove-in-cart').each(function(){
                var arr = [];
                arr.push($(this).data('product-id'));
                arr.push($(this).data('product-count'));
                arr.push($(this).data('vproduct-id'));
                cart.push(arr);
            });
            data.cart = cart;
        }

        for (var item in validates){
            if (validates[item] === 'error'){
                xhr = false;
            }
        }
        if(xhr){
            $.ajax({
                url: host + '/admin/order/order/create-order',
                async: false,
                type: 'post',
                data: data,
                beforeSend: function() {
                    $('.send-order').prop( "disabled", true );
                },
                success: function(res){
                    var data = JSON.parse(res);
                    if (data.status == 'success' || data.status == 'delete-cart'){
                        modalMessage(data.message,'Cпасибо');
                    }
                    if (data.status == 'error'){
                        modalMessage(data.message,'Ошибка');
                    }
                    if(data.status == 'delete-cart'){
                        $.ajax({
                            url: host + '/order/delete-cart',
                            type: 'post'
                        });
                    }
                    setTimeout(function(){window.location.replace('/');},1000);
                }
            });
        }
    });

    $('.warehouse-select').select2({
        placeholder: "Отделения",
        width: '100%',
        height:'50%',
        minimumResultsForSearch: Infinity,
        theme: "default",
        disabled:true
    });

    $('.settlement-select').select2({
        placeholder: "*Введите ваш город",
        language:"ru",
        ajax:{
            url: host + '/admin/order/order/ajax-search-settlement',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        },
        allowClear: false,
        theme: "default"
    });

    $('.settlement-select').on('select2:select', function (e) {
        var data = e.params.data;
        $('.warehouse-select').empty().trigger('change');
        $.ajax({
            type: 'POST',
            data:data,
            url: host + '/admin/order/order/ajax-get-warehouses',
            dataType: 'json',
            success: function (data) {
                $('input[name="Order[address]"]').val(data[0].text);
                $('.warehouse-select').select2({
                    width: '100%',
                    disabled:false,
                    data:data,
                    minimumResultsForSearch: Infinity,
                    theme: "default"
                });
            }
        });
    });
    
    $('.guest-order-send').on('click', function (e) {
        e.preventDefault();
        var yiiform = $('#ordering-guest');
        $.ajax({
            type: yiiform.attr('method'),
            url: yiiform.attr('action'),
            data: yiiform.serializeArray(),
            dataType: 'json',
            success: function (resp) {

                if (!resp.errors){
                    window.location.replace(resp.url);
                    return false;
                }

                $.each(yiiform[0], function () {
                    var el = $(this);

                    if (el.parent().hasClass('has-error')) {
                        el.parent().removeClass('has-error');
                        el.next().text('');
                    }
                    $.each(resp.errors, function (name, value) {
                        if (el.attr('name') == 'Guest[' + name + ']') {
                            el.parent().addClass('has-error');
                            el.next().text(value[0]);
                        }
                    });
                });
            }
        });
    });
});
