var v_product_field;

function getGalleryJSON() {
    var item = {};
    $('.gallery-box-content img').each(function (i, o) {
        item[i] = $(o).data('id');
    });
    return JSON.stringify(item);
}

function setGalleryJSON(gallery) {
    $('#gallery-item-serialize').val(gallery);
}

function addItemInGallery(obj) {
    var value = $('input[name="Product[gallery]"').data('value');
    var img = '<div class="gallery-box-item">\n\
    <span class="gallery-box-item-delete"><button class="btn btn-default"><i class="fa fa-trash" title="Удалить"></i></button></span>\n\
    <span class="gallery-box-item-default"><button class="btn btn-default"><input type="radio" name="Product[media_id]" title="По умолчанию" value="' + obj.id + '" ' + ((value == obj.id) ? 'checked' : '') + '/></button></span>\n\
    <span class="gallery-box-item-search"><button class="btn btn-default"><i class="fa fa-search-plus" title="Увеличить"></i></button></span>\n\
    <img src="' + host + '/admin' + obj.url + '" alt="' + obj.alt + '" data-id="' + obj.id + '" data-url="' + obj.url + '"></div>';
    $('.gallery-box-content').append(img);
    var gallery = getGalleryJSON();
    setGalleryJSON(gallery);
}

function isCheckedImg() {
    if ($('.gallery-box input').val() == '') {
        alert('Error');
        return;
    }
}

function getGalleryItem(img, active = false) {
    active = (active == true) ? 'active' : '';
    return '<div class="gallery-box-item">' +
            '<a href="#gallery-img" class="' + active + '">' +
            img +
            '<span class="checked glyphicon glyphicon-check"></span>' +
            '</a>' +
            '</div>';
}

function excludeImages(media_id = 0) {
    $.ajax({
        url: host + '/admin/product/product/ajax-get-media-id-all?id=' + media_id,
        type: 'POST',
        dataType: 'json',
        success: function (data) {
            for (var i = 0; i < data.length; i++) {
                $('.modal-body').find('.gallery-box').find('img[data-id=' + data[i] + ']').remove();
            }
        }
    });
}
$(document).ready(function () {
    $('.product-gallery-window').on('click', function (e) {
        $('.modal-body').empty();
        v_product_field = $(this).parent();
        var id = $(this).data('id');
        var product_id = $(this).data('product_id');
        var media_id = $(this).attr('data-media');
        $.ajax({
            url: host + '/admin/product/product/show-gallery-template',
            type: 'POST',
            success: function (template) {
                $('.modal-body').html(template);
                var gallery_box = $('.modal-body').find('.gallery-box');
                gallery_box.attr('data-v_product_id', id);
                gallery_box.attr('data-product_id', product_id);
                $('.gallery-box.gallery-product').find('img').each(function () {
                    active = ($(this).data('id') == media_id) ? true : false;
                    gallery_box.find('.gallery-box-content').append(getGalleryItem($(this)[0].outerHTML, active));
                });
            }
        });
        excludeImages(media_id);
    });

    $('body').on('click', '[href="#gallery-img"]', function (e) {
        e.preventDefault();
        var data = {};
        data['stock_id'] = $('.gallery-box').data('v_product_id');
        data['product_id'] = $('.gallery-box').data('product_id');
        if ($(this).hasClass("active")) {
            $(this).removeClass("active");
            $('.gallery-box input').val('');
            data['media_id'] = null;
        } else {
            $('.gallery-box-item a').removeClass("active");
            $(this).addClass("active");
            data['media_id'] = $(this).find('img').data('id');
        }
        $('.gallery-box input').val(JSON.stringify(data));
    });

    $('body').on('click', '[href="#insert-gallery-img"]', function (e) {
        e.preventDefault();
        isCheckedImg();
        var data = JSON.parse($('.gallery-box input').val());
        $.ajax({
            url: host + '/admin/product/product/ajax-save-v-product',
            type: 'POST',
            data: data,
            success: function (result) {
                if (result) {
                    v_product_field.find('.media_id').text(data['media_id']);
                    v_product_field.find('a').attr('data-media', (data['media_id'] == null) ? 0 : data['media_id']);
                }
            }
        });
    });

    if ($('.gallery-box-content').length) {
        $('#sortable').sortable({
            placeholder: '',
            containment: $('.gallery-box-content'),
            update: function (event, ui) {
                var gallery = getGalleryJSON();
                setGalleryJSON(gallery);
                $.ajax({
                    type: 'POST',
                    url: host + '/admin/product/product/update-position',
                    data: {'gallery': gallery}
                });
            }
        }).disableSelection();
    }

    $('body').on('click', '.gallery-box-item-delete', function () {
        $(this).parents('.gallery-box-item').remove();
        var gallery = getGalleryJSON();
        setGalleryJSON(gallery);
        return false;
    });

    if ($('.gallery-box-content').length > 0) {
        $.ajax({
            url: '/admin/product/product/ajax-show-gallery-item',
            type: 'post',
            data: {id: $('.gallery-box-content').data('id')},
            success: function (obj) {
                var obj = JSON.parse(obj);
                for (var i in obj) {
                    addItemInGallery(obj[i]);
                }
            }
        });
    }
});