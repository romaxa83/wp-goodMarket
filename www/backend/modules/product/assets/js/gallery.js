var v_product_field;
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
