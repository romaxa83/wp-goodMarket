function crudBoxShow(data) {
    $('#' + data.modal + ' .modal-title').text(data.title);
    $('#' + data.modal + ' input[type="text"]').val(data.name);
    $('#' + data.modal).modal('show');
}
function getProductCharacteristic() {
    var url = new URL(window.location.href);
    var id = url.searchParams.get('id');
    $.ajax({
        url: host + '/admin/product/product/ajax-get-product-characteristic',
        type: 'POST',
        data: {id: id},
        dataType: 'json',
        success: function (obj) {
            $('#product-characteristic').empty().append(obj);
        }
    });
}
function getProductCharacteristicList(id, val = '') {
    $.ajax({
        url: host + '/admin/product/product/ajax-get-product-characteristic-list',
        type: 'POST',
        dataType: 'json',
        data: {id: id},
        success: function (obj) {
            $('#characteristic-name').empty();
            for (var i in obj) {
                var item = new Option(obj[i]['name'], i, false, false);
                $(item).attr('data-type', obj[i]['type']);
                $('#characteristic-name').append(item);
            }
            $('#characteristic-name').val(val);
        }
    });
}
function clearCharacteristic() {
    $('#product-group_id').val('').trigger('change');
    $('#characteristic-name').empty();
    $('.field-productcharacteristic-value input').val('');
    $('.sp-input').val('').trigger('change');
}

function isEmptyObject(obj) {
    for (var i in obj) {
        if (obj.hasOwnProperty(i)) {
            return false;
        }
    }
    return true;
}
function checkButtonActive() {
    if ($('.crud-box').length > 0) {
        $('.crud').each(function () {
            if (!$(this).val()) {
                $('.crud-box-edit, .crud-box-delete').attr('disabled', 'disabled');
            }
        });
        $('.crud').on('change', function () {
            if ($(this).val()) {
                $(this).parents('.crud-box').find('.crud-box-edit').removeAttr('disabled');
                $(this).parents('.crud-box').find('.crud-box-delete').removeAttr('disabled');
            }
        });
    }
}

$(document).ready(function () {
    if ($.cookie('tab')) {
        $('a[href="' + $.cookie('tab') + '"]').click();
    }
    var typeItem = $('.type-item');
    var rating = $(this).val();
    $('.type-item-box').empty();
    $('#product-group_id').val(null);
    checkButtonActive();
    $('.crud-box-add').on('click', function () {
        //$(this).parents('.crud-box').find('select').val(null).trigger('change');
        crudBoxShow({
            id: null,
            name: null,
            title: $(this).data('title'),
            modal: $(this).data('modal')
        });
        return false;
    });
    $('.crud-box-edit').on('click', function () {
        var data = {
            id: $(this).parents('.crud-box').find('select').select2('data')[0].id,
            name: $(this).parents('.crud-box').find('select').select2('data')[0].text,
            title: $(this).data('title'),
            modal: $(this).data('modal')
        };
        if (Object.keys(data).length > 0) {
            crudBoxShow(data);
        }
        return false;
    });
    $('.crud-box-delete').on('click', function () {
        var id = $(this).parents('.crud-box').find('select').select2('data')[0].id;
        var data_id = $(this).data('id');
        var select = $('#' + data_id);
        $.ajax({
            url: $(this).data('url'),
            type: 'POST',
            data: {id: id},
            success: function (data) {
                data = $.parseJSON(data);
                for (var i = 0; i < data.length; i++) {
                    $('#' + data[i] + ' option[value="' + id + '"]').remove();
                }
                $('.type-item').each(function () {
                    $(this).find('input').val('');
                });
                clearCharacteristic();
                checkButtonActive()
                getProductCharacteristic();
            }
        });
        return false;
    });
    $('.crud-box-save').on('click', function () {
        var item = $(this);
        var modal = $(this).parents('.modal');
        var select = $('#' + $(this).data('id'));
        var id = select.select2('val');
        var data = modal.find('form').serializeArray();
        data.push({name: 'id', value: id});
        $.ajax({
            url: $(this).data('url'),
            type: 'POST',
            //dataType:'json',
            data: data,
            success: function (obj) {
                var obj = JSON.parse(obj);
                modal.find('.form-group').removeClass('has-error');
                if (obj.type == 'error') {
                    item.parents('.modal').find('[name="' + obj.name + '"]').parents('.form-group').addClass('has-error');
                    item.parents('.modal').find('[name="' + obj.name + '"]').parents('.form-group').find('.help-block-error').text(obj.msg);
                } else {
                    if (select.find("option[value=" + obj.id + "]").length) {
                        select.select2('data')[0].text = obj.value;
                        select.select2('data')[0].id = obj.id;
                        select.trigger("change");
                    } else {
                        var newState = new Option(obj.value, obj.id, true, true);
                        select.append(newState).trigger('change');
                    }
                    modal.find('.form-group').removeClass('has-error');
                    modal.find('.help-block-error').empty();
                    modal.find('input[type="text"]').val('');
                    modal.modal('hide');
                }
            }
        });
        return false;
    });


    if ($('#product-group_id').length > 0) {
        getProductCharacteristic();
        $('#product-group_id').on('change', function () {
            var id = $('#product-group_id').val();
            $('#product-characteristic-modal').find('input[name="group_id"]').val(id);
            getProductCharacteristicList($(this).val());
        });
    }
    $('#characteristic-name').on('change', function () {
        var type = $(this).find('option:selected').data('type');
        $('.type-item').addClass('hidden');
        $('.type-item.type-' + type).removeClass('hidden');
        $('.type-item.type-' + type).find('input').val('');
    });

    $('.save-characteristic').on('click', function () {
        var data = {
            product_id: $('#form-product').data('product-id'),
            group_id: $('#product-group_id').val(),
            characteristic_id: $('#characteristic-name').val(),
            value: $('.field-productcharacteristic-value input').val(),
            edit: $(this).attr('data-edit')
        };
        clearCharacteristic();
        $.ajax({
            url: host + '/admin/product/product/ajax-save-product-characteristic',
            type: 'POST',
            data: data,
            success: function (obj) {
                $('.type-item').addClass('hidden');
                $('#characteristic-name').parents('.col-md-4').addClass('hidden')
                getProductCharacteristic();
            }
        });
    });

    $('.add-select-group').on('click', function () {
        $('#product-characteristic-modal input[name="group_id"]').val($('#product-group_id').val());
    });
    $('#product-group_id').on('change', function () {
        $('#characteristic-name').parents('.hidden').removeClass('hidden');
    });

    $('.life-edit-price').on('click', function (e) {
        var value = parseFloat($(this).text());
        var width = $(this).width();
        if (!isNaN(value)) {
            $(this).empty().append('<input type="number" min="0" step="0.01" value="' + value + '" style="width: ' + width + 'px;"/>');
            $(this).find('input').focus();
        }
    });
    $('.life-edit-price').on('focusout', function () {
        var obj = $(this);
        var stock_id = parseInt(obj.data('stock-id'));
        var product_id = parseInt(obj.data('product-id'));
        var price = parseFloat(obj.find('input').val());
        $.ajax({
            url: host + '/admin/product/product/set-price-vproduct',
            type: 'POST',
            data: {stock_id: stock_id, product_id: product_id, price: price},
            success: function (price) {
                obj.empty().append(price);
                let row = obj.parent();
                sale = parseFloat(row.find('td.cell-sale').text());
                price = parseFloat(price);
                row.find('td.cell-sale_price').text(parseFloat(price * (1 - sale / 100)).toFixed(4));
            }
        });
    });

    $('.status-toggle-v-product').on('change', function () {
        var checked = $(this).is(':checked') ? 1 : 0;
        var id = $(this).data('stock_id');
        var product = $(this).data('product_id');
        var url = $(this).data('url');
        var attr = $(this).data('char-value');
        $.post(url, {stock_id: id, product_id: product, publish: checked, char_value: attr});
    });

    $('#form-product').on('afterValidate', function () {
        if ($('.has-error').length > 0) {
            var id = $('.has-error').first().parents('.tab-pane').attr('id');
            $('a[href="#' + id + '"]').click();
        }
    });

    $('input[name="Product[rating]"]').on('focusout', function () {
        if (rating != $(this).val()) {
            rating = $(this).val();
            $('input[name=prod_rating]').val(rating);
        }
    });

    $('#form-product .nav-tabs li').on('click', function () {
        var href = $(this).find('a').attr('href');
        $.cookie('tab', href);
    });

    $('#product-price').on('change', function () {
        var tradePrice = $('#product-trade-price').val();
        if (parseFloat(tradePrice) > parseFloat($(this).val())) {
            warning('Warning', 'Цена установлена меньше оптовой цены');
        }
    });

    $('body').on('click', '.delete-characteristic', function () {
        var id = $(this).data('id');
        $.ajax({
            type: 'POST',
            url: host + '/admin/product/product/ajax-delete-characteristic',
            data: {id: id},
            success: function (html) {
                getProductCharacteristic();
            }
        });
    });

    $('body').on('click', '.edit-characteristic', function () {
        var id = $(this).data('id');
        $.ajax({
            type: 'POST',
            url: host + '/admin/product/product/ajax-get-characteristic-params',
            dataType: 'json',
            data: {id: id},
            success: function (data) {
                let field;
                $('#productcharacteristic-value').parents('.type-item.type-color').removeClass('hidden');
                $('#product-group_id').val(data.group).trigger('change');
                getProductCharacteristicList(data.group, data.characteristic);
                $('#characteristic-name').parents('.col-md-4').removeClass('hidden');
                field = $('.type-item.type-' + data.type);
                field.removeClass('hidden');
                field.find('.save-characteristic').attr('data-edit', id);
                if (data.type == 'text') {
                    $('.type-item.type-color').addClass('hidden');
                    field.find('input').val(data.value).trigger('change');
                } else {
                    $('.type-item.type-text').addClass('hidden');
                    $('.sp-input').val(data.value).trigger('change');
                }
            }
        });
    });

});