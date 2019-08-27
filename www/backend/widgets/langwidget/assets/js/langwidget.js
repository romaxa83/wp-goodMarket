$(document).ready(function () {
    $('.language-tab-box input, .language-tab-box textarea').on('blur', function () {
        var form_group = $(this).parents('.form-group');
        if (form_group.hasClass('required')) {
            if ($(this).val().length == 0) {
                form_group.addClass('has-error');
                var text = form_group.find('label').text();
                form_group.find('.help-block').text('Необходимо заполнить «' + text + '».');
            } else {
                form_group.removeClass('has-error');
                form_group.find('.help-block').empty();
            }
        }
    });
});