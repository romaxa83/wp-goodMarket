var lang_select = $('select[name="lang_list_new"]');
var category_select = $('select[name="category_list_new"]');
var product_select = $('select[name="product_list_new"]');
var v_product_select = $('select[name="vproduct_list_new"]');
var manage_table = $('#manage-table-order-products');
var count_products_input = $('input[name=good-count]');
var products_table  = $('#order-products-table');

document.addEventListener("DOMContentLoaded", init);


$('body').on('select2:select', '.settlement-select', function (e) {
    var data = e.params.data;
    var url = host + '/admin/order/order/ajax-get-warehouses';
    fillSelect(url, $('select[name="Order[address]"]'), {id:data.id});
});

$('body').on('select2:unselect', '.settlement-select', function (e) {
    $('.settlement-select').parent().find('.select2-selection__rendered').attr('title','');
});

$('.field-order-delivary select').on('change', function(){
    if($(this).val()==2){
        $('.pickup').addClass('pickup-hide');
        $('.address-delivary').removeClass('address-delivary-hide');
        $('.address-delivary').attr('data-required','true');
        $('.pickup').attr('data-required','false');
    }else{
        $('.pickup').removeClass('pickup-hide');
        $('.address-delivary').addClass('address-delivary-hide');
        $('.address-delivary').attr('data-required','false');
        $('.pickup').attr('data-required','true');
    }
});

$('.field-order-user_status select').on('change', function(){
   if($(this).val()==2){
        $('select[name="Order[user_id]"]').parent().css('display','none');
        $('.guest').removeClass('guest-hide');
        $('input[name="Guest[phone_one_click]"]').parent().show();
   }else{
        $('select[name="Order[user_id]"]').parent().show();
        $('.guest').addClass('guest-hide');
        $('input[name="Guest[phone_one_click]"]').parent().hide();
   }
});

$('.field-order-order_status select').on('change', function(){
   if($(this).val()==2){
        $('.full-order').hide();
        $('.short-order').show();
   }else{
        $('.full-order').show();
        $('.short-order').hide();
   }
});

function fillSelect(url, select, data={}, value=0){
    return $.ajax({
        type: 'POST',
        url: url,
        dataType:'json',
        data:data,
        success: function (data) {
            select.empty();
            if(data!=false){
                for (var i in data) {
                    var item = new Option(data[i]['text'], data[i]['id'], false, false);
                    select.append(item);
                }
                var obj_keys = Object.keys(data);
                if(value==0){
                    value = data[obj_keys[0]]['id'];
                }
            }else{
                select.append(new Option('Ничего не найдено', 0, false, false));
            }
            select.removeAttr('disabled');
            $(select).attr('data-selected_id', value);
            $(select).val(value).trigger('change');
        }
    });
}

function clearForm(){
    lang_select.val(null).trigger('change');
    category_select.val(null).trigger('change');
    product_select.val(null).empty().trigger('change');
    product_select.prop('disabled',true);
    v_product_select.val(null).empty().trigger('change');
    v_product_select.prop('disabled',true);
    count_products_input.val(1);
}

function reloadProductsTable(data){
    clearForm();
    $.ajax({
        type: 'POST',
        url:  host + '/admin/order/order/ajax-reload-products-table',
        data: data,
        success: function (response) {
            response = JSON.parse(response);
            $('.products-table').html(response.products_table);
            $('input[name="order_summ"]').val(response.order_summ);
        }
    });
}

function getUrlParams(){
     return window
            .location
            .search
            .replace('?','')
            .split('&')
            .reduce(
                function(p,e){
                    var a = e.split('=');
                    p[ decodeURIComponent(a[0])] = decodeURIComponent(a[1]);
                    return p;
                },
                {}
            );
}

function init(){
    var sett_title =  $('.settlement-select').find('option:selected').attr('value');
    var url = host + '/admin/order/order/ajax-get-warehouses-by-settlement?settlement='+sett_title;
    var wh_selected;
    var params = getUrlParams();
    var order_id = params.id;
    var user_status_select =  $('.field-order-user_status select');
    if(user_status_select.val()==2){
        $('select[name="Order[user_id]"]').parent().css('display','none');
        $('.guest').removeClass('guest-hide');
   }else{
        $('select[name="Order[user_id]"]').parent().show();
        $('.guest').addClass('guest-hide');
   }
    if (order_id){
        $.ajax({
            type: 'POST',
            url:  host + '/admin/order/order/ajax-get-products-by-order?order_id='+order_id,
            dataType:'json',
            success: function(data) {
                setProducts(data);
            }
        });
    }
   if($('.warehouse-select option').length > 1){
       wh_selected =  $('select[name="Order[address]"]').find('option:selected').attr('value');
       fillSelect(url, $('select[name="Order[address]"]'), {}, wh_selected);
    }
}

function getAddedProducts(){
    return JSON.parse(manage_table.parent().find('input[name="products_data"]').val());
}

// рассчитывает скидку, заносит возвращенные данные в input[name="products_data"] и переотображает таблицу товаров
function updateProductsData(one_product, all_products){
    var params = getUrlParams();
    var order_id = (params.id != undefined) ? params.id : 0;
    $.ajax({
        type: 'POST',
        url:  host + '/admin/order/order/ajax-set-kit-sale',
        dataType:'json',
        data:{'order_id':order_id, 'product':one_product, 'product_list':all_products},
        success: function(data) {
            setProducts(data);
            reloadProductsTable({data});
        }
    });
}

function setProducts(data){
    manage_table.parent().find('input[name="products_data"]').val(JSON.stringify(data));
}

function getProductPrice(lang_id, category_id, product_id, vproduct_id){
    var params = getUrlParams();
    var order_id = (params.id != undefined) ? params.id : 0;
    var products = getAddedProducts();

    if(Object.keys(products).length == 0){
        products='empty';
    }
    $.ajax({
        type: 'POST',
        url:  host + '/admin/order/order/ajax-get-product-price?order_id='+order_id,
        dataType: 'json',
        data:{products:products, lang_id:lang_id, category_id:category_id, product_id:product_id, vproduct_id:vproduct_id},
        success: function(data) {
            if (data.type == "error") {
                if (data.redirect != undefined) {
                    window.location.href = host + data.redirect;
                }
            }
            if(data.type == "success"){
                manage_table.find('tbody tr:first').attr('data-price', data['price']).attr('data-product_price', data['product_price']).attr('data-currency', data['currency']);
            }
        }
    });
}

function callProductSelect(product_id){
    var category_id = category_select.attr('data-selected_id');
    var url = host + '/admin/order/order/ajax-get-product-variations';
    $.when(fillSelect(url, v_product_select, {category_id:category_id, product_id:product_id})).then(function(){
        product_select.attr('data-selected_id', product_id);
        v_product_id = $(v_product_select).attr('data-selected_id');
        v_product_select.attr('data-selected_id',v_product_id);
        getProductPrice(lang_select.attr('data-selected_id'), category_id, product_id, v_product_id);
    });
}

$('#form-order').on('afterValidate', function () {
    if ($('.has-error').length > 0) {
        var id = $('.has-error').first().parents('.tab-pane').attr('id');
        $('a[href="#' + id + '"]').click();
    }
});

$(lang_select).on('select2:select', function (e) {
    var data = e.params.data;
    var lang_id = data.id;
    $(this).attr('data-selected_id', lang_id);
    category_select.empty();
    product_select.empty();
    v_product_select.empty();
    var url =  host + '/admin/order/order/ajax-get-categories?lang_id='+lang_id;
    $.when(fillSelect(url, category_select)).then(function(){
        category_id = $(category_select).val();
        var url =  host + '/admin/order/order/ajax-get-category-products?category_id='+category_id+'&lang_id='+lang_id;
        $.when(fillSelect(url, product_select)).then(function(){
            product_id = $(product_select).val();
            callProductSelect(product_id);
        })
    })
});

$(category_select).on('select2:select', function (e) {
    var data = e.params.data;
    var category_id = data.id;
    $(this).attr('data-selected_id', category_id);
    product_select.empty();
    v_product_select.empty();
    var url =  host + '/admin/order/order/ajax-get-category-products?category_id='+category_id+'&lang_id='+lang_select.val();
    $.when(fillSelect(url, product_select)).then(function(){
        product_id = $(product_select).val();
        callProductSelect(product_id);
    })
});

$(product_select).on('select2:select', function (e) {
    var data = e.params.data;
    var product_id = data.id;
    v_product_select.empty();
    callProductSelect(product_id);
});

$(v_product_select).on('select2:select', function (e) {
    var data = e.params.data;
    var vproduct_id = data.id;
    var lang_id = lang_select.attr('data-selected_id');
    var category_id = category_select.attr('data-selected_id');
    var product_id = product_select.attr('data-selected_id');
    v_product_select.attr('data-selected_id', vproduct_id);
    getProductPrice(lang_id, category_id, product_id, vproduct_id);
});

$('.save-product').on('click', function() {
    var params = getUrlParams();
    var order_id = params.id;
    var data = getAddedProducts();
    var new_data = {
        product_id: product_select.attr('data-selected_id'),
        lang_id: lang_select.attr('data-selected_id'),
        vproduct_id: v_product_select.attr('data-selected_id'),
        category_id: category_select.attr('data-selected_id'),
        count: manage_table.find('tbody tr input').val(),
        product_price: manage_table.find('tbody tr').attr('data-product_price'),
        price: manage_table.find('tbody tr').attr('data-price'),
        currency: manage_table.find('tbody tr').attr('data-currency')
    };
    if(order_id) { // при редактировании существующего заказа
        $.ajax({
            type: 'POST',
            url:  host + '/admin/order/order/ajax-save-product?order_id='+order_id,
            data: {new_prod:new_data, products:JSON.stringify(data)},
            dataType:'json',
            success: function (data){
                if(data!=false){
                    updateProductsData(new_data, data);
                }
            }
        });
    } else { // при создании нового заказа
        if(Object.keys(data).length>0){
            for (var i = 0; i < Object.keys(data).length; i++) {
                if(data[i].product_id==new_data.product_id && data[i].vproduct_id==new_data.vproduct_id){//если такой товар есть в заказе
                        data[i]=new_data;
                        updateProductsData(new_data, data);
                        return;
                }
            }
        }
        // если такого товара (product_id + vproduct_id) в заказе ещё нет
        if(typeof new_data.product_id!="undefined" && typeof new_data.vproduct_id!="undefined"){
            var key = Object.keys(data).length;
            data[key] = new_data;
            updateProductsData(new_data, data);
        }
    }
});

$('body').on('click','.edit-order-product',function(){
    var index = $(this).data('index');
    index = index.split('-');
    var record = _.find(getAddedProducts(), {'product_id': index[0], 'vproduct_id': index[1]});
    lang_select.val(record.lang_id).trigger('change');
    lang_select.attr('data-selected_id', record.lang_id);

    var url =  host + '/admin/order/order/ajax-get-categories?lang_id='+record.lang_id;
    $.when(fillSelect(url, category_select, {}, record.category_id)).then(function(){
        url =  host + '/admin/order/order/ajax-get-category-products?category_id='+record.category_id+'&lang_id='+record.lang_id;
        $.when(fillSelect(url, product_select, {}, record.product_id)).then(function(){
            url = host + '/admin/order/order/ajax-get-product-variations';
            $.when(fillSelect(url, v_product_select, {category_id:record.category_id, product_id:record.product_id}, record.vproduct_id)).then(function(){
                count_products_input.val(record['count']);
                getProductPrice(record.lang_id ,record.category_id, record.product_id, record.vproduct_id);
            })
        })
    });
});

function update(data){
    if(data=='empty'){
        data = [];
    }
    setProducts(data);
    reloadProductsTable({data});
}

$('body').on('click', '.delete-order-product',function(){
    var index = $(this).data('index').split('-');
    var products = getAddedProducts();
    var params = getUrlParams();
    var order_id = params.id;
    var products_count = products.length;

    if(order_id!=undefined){ // при редактировании существующего заказа
        if(parseInt(products_count)==1){
            if (window.confirm("Вы действительно хотите удалить заказ?")){
                $.ajax({
                    type: 'POST',
                    url:  host + '/admin/order/order/ajax-delete-order?order_id='+order_id,
                    success: function (data){
                        if(data){
                            window.location.replace(host + '/admin/order/order/');
                        }
                    }
                });
            }
            return false;
        }
        $.ajax({
            type: 'POST',
            url:  host + '/admin/order/order/ajax-delete-product?order_id='+order_id,
            data:{products:products, index:index},
            dataType:'json',
            success: function (data){
                if(data!=false){
                    product = _.remove(products, function(n) {
                        return n['product_id'] == index[0] && n['vproduct_id'] == index[1];
                    });
                    updateProductsData(product, products);
                }
            }
        });
    } else { // при создании нового
        product = _.remove(products, function(n) {
            return n['product_id'] == index[0] && n['vproduct_id'] == index[1];
        });
        updateProductsData(product, products);
    }
});

$('body').on('click', '.show-order-product',function(e){
    $('.modal').find('.modal-body').empty();
    var modal = $('.modal');
    var modal_body = $('.modal').find('.modal-body');
    var index = $(this).data('index');
    var data = getAddedProducts();
    $.ajax({
        type: 'POST',
        url:  host + '/admin/order/order/ajax-show-product-data',
        data: {product:data[index]},
        success: function (html){
            if(html!=false){
                modal_body.html(html);
                //modal.modal('show');
            }
        }
    });
});

$('.attribut-order').on('change',function(){
    var attributOrder = $(this);
    var value = $(this).val();
    var orderId = $(this).closest('tr').attr('data-key');
    var field = $(this).attr('data-field');
    $.ajax({
        url : '/admin/order/order/change-attribut',
        type : 'post',
        data : {value : value, id : orderId, field : field},
        beforeSend : function(){
            $('body').fadeTo( "slow", 0.33 );
        },
        complete : function(){
            $('body').fadeTo( "slow", 1 );
            if (field == 'status' && value != 1) {
                let payment = attributOrder.closest('tr').find('td[data-attr="payment"]');
                $(payment).children('select').each(function () {
                    $(this).attr('disabled', 'disabled');
                });
            } else if (field == 'status' && value == 1) {
                let payment = attributOrder.closest('tr').find('td[data-attr="payment"]');
                $(payment).children('select').each(function () {
                    $(this).removeAttr('disabled');
                });
            }
        }
    });
});
