$(document).ready(function () {

    $('.all_delete_assignment').on('click', function (e) {
        e.preventDefault();
        var item = {};
        var check = $('input[name="delete[]"]:checked');
        $.each(check, function (index, value) {
            item[index] = $(value).val();
        });
        $.post( host + "/admin/users/roles/roles-list/all-delete", item)
            .done(function(data) {
                window.location.reload()
            });
    });

});