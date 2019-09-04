$(document).ready(function () {
    var fixHelperModified = function (e, tr) {
        var $originals = tr.children();
        var $helper = tr.clone();
        $helper.children().each(function (index) {
            $(this).width($originals.eq(index).outerWidth() + 30);
        });
        return $helper;
    };
    $('#banner-grid tbody').sortable({
        placeholder: 'ui-state-highlight',
        axis: "y",
        cursor: "move",
        helper: fixHelperModified,
        update: function (event, ui) {
            var data = $(this).sortable('serialize');
            $.ajax({
                type: 'POST',
                url: host + '/admin/banners/banners/update-position',
                data: data
            });
        }
    }).disableSelection();
});
