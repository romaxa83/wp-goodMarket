var protocol = location.protocol;
var slashes = protocol.concat("//");
var port = location.port;
var path = $('body').data('url');
var host = (port) ? slashes.concat(window.location.hostname) + ':' + port : slashes.concat(window.location.hostname);

var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
};

$(document).ready(function () {
    /* --- iCheck --- */
    $('.custom-checkbox, .custom-radio').iCheck({
        checkboxClass: 'icheckbox_minimal-red',
        radioClass: 'iradio_minimal-red'
    });
    $('.status-toggle').on('change', function () {
        var checked = $(this).is(':checked') ? 1 : 0;
        var id = $(this).data('id');
        var url = $(this).data('url');
        $.post(url, {id: id, checked: checked});
    });
    var check = false;
    $('.select-all-header .custom-checkbox').on('ifClicked', function (evt) {
        var $checkboxes = $('.grid-view .custom-checkbox');

        if (check === false) {
            $checkboxes.iCheck('check');
            check = true;
        } else {
            $checkboxes.iCheck('uncheck');
            check = false;
        }
    });
    $('.element-check .custom-checkbox').on('ifChanged', function (evt) {
        var $all = $('.element-check .custom-checkbox');
        var $checked = $all.filter(':checked');

        if ($checked.length === $all.length) {
            $('.select-all-header .custom-checkbox').iCheck('check');
            check = true;
        } else {
            $('.select-all-header .custom-checkbox').iCheck('uncheck');
            check = false;
        }
    });
    /* --- /iCheck --- */

    /* --- Language Tab --- */
    if ($('.language-tab-box').length != 0) {
        var id = $(this).find('.has-error').first().parents('.tab-pane.fade').attr('id');
        if (id)
            $('a[href="#' + id + '"]').first().click();
    }
    /* --- /Language Tab --- */
});