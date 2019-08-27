$(document).ready(function () {
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
        $.post(host + "/admin/users/administrators/administrators/all-delete", item)
            .done(function(data) {
                window.location.reload()
            });
    });
});