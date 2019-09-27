var protocol = location.protocol;
var slashes = protocol.concat("//");
var port = location.port;
var path = $('body').data('url');
var host = (port) ? slashes.concat(window.location.hostname) + ':' + port : slashes.concat(window.location.hostname);
var current_item;

$(document).mouseup(function (e) {
    if (current_item != null) {
        var current_form = current_item.find('form');
        if (!$(current_form).is(e.target) && $(current_form).has(e.target).length === 0) {
            current_form.remove();
        }
    }
});

$('body').on('click', '.col-reviews .add', function () {
    var form = $(this).parents('form');
    var text_review = form.find('textarea').val();
    var action = $(this).data('action');
    var rating = form.find('#reviewform-rating').val();
    var product_id = $(this).data('product_id');
    var user_id = $(this).data('user_id');
    var item = $(this).parents('.item');
    var parent_id = item.data('id');
    var input_id;
    $.ajax({
        type: form.attr('method'),
        url: host + '/reviews/validate/?action=' + action,
        data: form.serializeArray()
    }).done(function (data) {
        if (data.success) {
            $.ajax({
                url: host + '/admin/reviews/reviews/add-review',
                type: form.attr('method'),
                data: {'text': text_review, 'rating': rating, 'product_id': product_id, 'user_id':user_id, 'parent_id': parent_id, 'action': action},
                success: function (response)
                {
                    var params = location.search.split('&');
                    $.pjax({url: window.location.href.replace(params[1],'page=1'), container: '#reviews-product',timeout : 4000});
                    if ($('#list-wrapper').find(':last').hasClass('empty')) {
                        $('.empty').remove();
                    }
                    $(form).trigger('reset');
                    $('#reviewform-verifycode-image').trigger('click');
                }
            });
        } else if (data.validation) {
            form.yiiActiveForm('updateMessages', data.validation, true);
        }
    });
});

$('body').on('click', '.answer', function () {
    var item = $(this).parents('.item');
    current_item = item;
    var review_id = item.data('id');
    $.ajax({
        url: host + '/admin/reviews/reviews/show-answer-form',
        type: 'post',
        data: {'review_id': review_id},
        success: function (response)
        {
            item.append(response);
        }
    });               
});

function updateStats(product_id) {
    var url = host + '/admin/reviews/reviews/update-stats';
    var color = $(".review.stats").find('.rating').data('color');
    $.post(url, {color:color, product_id: product_id}, function (data) {
        $(".review.stats").replaceWith(data);
    });
}

$('#reviewform-verifycode').on('blur', function(){
    testCaptcha();
});

$('#reviewform-verifycode-image').on('click',function(){
    $('#reviewform-verifycode').val('');
    testCaptcha();
});

function testCaptcha(){
    var code = $('#reviewform-verifycode').val();
    var src = $('#reviewform-verifycode-image').attr('src').split('?')[0];
    $('.col-reviews .add').attr('disabled','disabled');
    if(code){
        $.ajax({
            url : '/site/validate-captcha',
            type : 'post',
            data : {code, src},
            success : function(res){
                if(!res){
                    $('.field-reviewform-verifycode').removeClass('has-success');
                    $('.field-reviewform-verifycode').addClass('has-error');
                    $('.field-reviewform-verifycode').find('.help-block').text('Не правильный код с картинки');
                }else{
                    $('.field-reviewform-verifycode').addClass('has-success');
                    $('.field-reviewform-verifycode').removeClass('has-error');
                    $('.field-reviewform-verifycode').find('.help-block').empty();
                    $('.col-reviews .add').removeAttr('disabled');
                }
            }
        });
    }
}

$('#reviews-product').on('pjax:beforeSend',function(e){
    $('#preloader').show();
});

$('#reviews-product').on('pjax:end', function() {
    $('#preloader').hide();
});