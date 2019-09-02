var sortable = $('#banner-grid tbody');

$(document).ready(function () {
    $(sortable).sortable({
        placeholder: 'ui-state-highlight',
        update: function (event, ui) {
            var data = $(this).sortable('serialize');
            $.ajax({
                type: 'POST',
                url: host + '/admin/banners/banners/update-positions',
                data: data
            });
        }
    }).disableSelection();
});
