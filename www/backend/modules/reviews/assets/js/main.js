var parent_id;
var table = $('.review-table');
var current_row;
$('body').on('click', '.answer-window', function (e) {
    parent_id = $(this).data('parent_id');
    current_row = $(this).parents('tr');
    $.ajax({
        url: host + '/admin/reviews/reviews/show-answer-form-back',
        success: function (data) {
            $('.modal-body').html(data);
        }
    });
});
$('body').on('click', '.add', function () {
    var form = $(this).parents('form');
    var text_review = form.find('textarea').val();
    var action = $(this).data('action');
    var user_id =  $(this).data('user_id');
    $.ajax({
        type: form.attr('method'),
        url: host + '/admin/reviews/reviews/validate/?action=' + action,
        data: form.serializeArray(),
    }).done(function (data) {
        if (data.success) {
            $.ajax({
                url: host + '/admin/reviews/reviews/add-review',
                type: 'post',
                data: {'text': text_review, 'parent_id': parent_id, 'user_id':user_id, 'action': action},
                success: function (){
                    window.location.reload();
                }
            });
        } else if (data.validation) {
            form.yiiActiveForm('updateMessages', data.validation, true); // renders validation messages at appropriate places
        }
    });
});
