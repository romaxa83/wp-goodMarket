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
                select.append(new Option('Ничео не найдено', 0, false, false));
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
    manage_table.find('tr').attr('data-edit', '');
}

function getOrderSumm(){
    let data = getAddedProducts();
    let prices = [];
    let summ = 0;
    data = Object.values(data);
    prices = data.map(function(value,index) {
        return value['price'] * value['count'];
    });
    summ = prices.reduce((a, b) => a + b, 0);
    return summ;
}

function reloadProductsTable(data){
    clearForm();
    $.ajax({
        type: 'POST',
        url:  host + '/admin/order/order/ajax-reload-products-table',
        data: data,
        success: function (html) {
            $('.products-table').html(html);
            $('input[name="order_summ"]').val(parseFloat(getOrderSumm()).toFixed(4));
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
    var order_status_select = $('.field-order-order_status select');
    var delivary_select = $('.field-order-delivary select');
    if(user_status_select.val()==2){
        $('select[name="Order[user_id]"]').parent().css('display','none');
        $('.guest').removeClass('guest-hide');
   }else{
        $('select[name="Order[user_id]"]').parent().show();
        $('.guest').addClass('guest-hide');
   }
   if($('.warehouse-select option').length > 1){
       wh_selected =  $('select[name="Order[address]"]').find('option:selected').attr('value');
       fillSelect(url, $('select[name="Order[address]"]'), {}, wh_selected);
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
            console.log('Данные что вернул ajax-set-kit-sale: ', data);
            setProducts(data);
            reloadProductsTable({data});
        }
    });
}

function setProducts(data){
    manage_table.parent().find('input[name="products_data"]').val(JSON.stringify(data));
    console.log('Данные в input[name="products_data"]: ', getAddedProducts());
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
        data:{products:products, lang_id:lang_id,category_id:category_id, product_id:product_id, vproduct_id:vproduct_id},
        success: function(data) {
            if(data!=false){
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
        getProductPrice(lang_select.attr('data-selected_id'),category_id, product_id, v_product_id);
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
    var product_id = product_select.data('selected_id');
    v_product_select.attr('data-selected_id', vproduct_id);
    getProductPrice(lang_id, category_id, product_id, vproduct_id);
});

$('.save-product').on('click', function() {
    var params = getUrlParams();
    var order_id = params.id;
    var edit_index = manage_table.find('tr').attr('data-edit');
    // console.log('edit_index', edit_index);
    edit_index = (edit_index!='' && edit_index!=undefined)?edit_index:null;
    // console.log('edit_index', edit_index);
    var data = getAddedProducts();
    var new_data = {
        product_id: product_select.attr('data-selected_id'),
        lang_id: lang_select.attr('data-selected_id'),
        vproduct_id: v_product_select.attr('data-selected_id'),
        category_id:category_select.attr('data-selected_id'),
        count: manage_table.find('tbody tr input').val(),
        product_price: manage_table.find('tbody tr').attr('data-product_price'),
        price: manage_table.find('tbody tr').attr('data-price'),
        currency: manage_table.find('tbody tr').attr('data-currency')
    };
    if(order_id) { // при редактировании существующего заказа
        $.ajax({
            type: 'POST',
            url:  host + '/admin/order/order/ajax-save-product?order_id='+order_id,
            data: {replace_index:edit_index, new_prod:new_data, products:JSON.stringify(data)},
            dataType:'json',
            success: function (data){
                if(data!=false){
                    console.log('Данные что вернул ajax-save-product: ', data);
                    updateProductsData(new_data, data);
                }
            }
        });
    } else { // при создании нового
        if(Object.keys(data).length>0){
            for (var i = 0; i < Object.keys(data).length; i++) {
                if(data[i].product_id==new_data.product_id && (data[i].vproduct_id==new_data.vproduct_id || data[i].vproduct_id==0)){
                        data[i]=new_data;
                        console.log('data: ',data);
                        console.log('new_data: ',new_data);
                        // return false;
                        updateProductsData(new_data, data);
                        return;
                }
            }
        }
        if(edit_index!='' && edit_index!=undefined){
            data[edit_index] = new_data;
            console.log(1);
           // data.splice(edit_index,1);
        }else{
            if(typeof new_data.product_id!="undefined" && typeof new_data.vproduct_id!="undefined"){
                console.log(2);
                var key = Object.keys(data).length;
                console.log('key: ', key);
                console.log('data: ', data);
                console.log('new_data: ', new_data);
                data[key] = new_data;
                updateProductsData(new_data, data);
            }
        }

    }
});

$('body').on('click','.edit-order-product',function(){
    var index = $(this).data('index');
    console.log(index);
    manage_table.find('tr').attr('data-edit', index);
    var data = getAddedProducts();
    var record = data[index];
    lang_select.val(record.lang_id).trigger('change');
    lang_select.attr('data-selected_id', record.lang_id);
    category_select.val(record.category_id).trigger('change');
    category_select.attr('data-selected_id', record.category_id);
    var url =  host + '/admin/order/order/ajax-get-category-products?category_id='+record.category_id+'&lang_id='+record.lang_id;
    fillSelect(url, product_select, {}, record.product_id);
    var url = host + '/admin/order/order/ajax-get-product-variations';
    fillSelect(url, v_product_select, {category_id:record.category_id, product_id:record.product_id}, record.vproduct_id);
    count_products_input.val(record['count']);
    getProductPrice(record.lang_id ,record.category_id, record.product_id, record.vproduct_id);
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
    var product = _.find(products, {'product_id': index[0], 'vproduct_id': index[1]});
    var params = getUrlParams();
    var order_id = params.id;
    var products_count = products.length;
    // console.log(order_id);
    // console.log(product);
    // console.log(products);
    // console.log(product);
    // return false;

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
                    console.log('После удаления осталось в products : ', products);
                    // return false;
                    updateProductsData(product, products);
                    // setProducts(products);
                    // reloadProductsTable(products);
                }
            }
        });
    } else { // при создании нового
        product = _.remove(products, function(n) {
            return n['product_id'] == index[0] && n['vproduct_id'] == index[1];
        });
        console.log('После удаления осталось в products : ', products);
        // return false;
        updateProductsData(product, products);
        // setProducts(products);
        // reloadProductsTable(products);
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
        }
    });
});
