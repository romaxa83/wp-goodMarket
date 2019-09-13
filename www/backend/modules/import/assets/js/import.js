var action = $('#menu1').data('action');
var protocol = location.protocol;
var slashes = protocol.concat("//");
var port = location.port;
var path = $('body').data('url');
var host = (port) ? slashes.concat(window.location.hostname) + ':' + port : slashes.concat(window.location.hostname);
var first_state_link;

function formattedConfigImport(data) {
    var data = JSON.parse(data);
    $('.tag-from-file').html(data.data.configField);
    $('.category-from-file').html(data.data.configCategory);
    for (var i = 0; i < data.data.characterVal.length; i++) {
        $('#characteristic-field option:contains(' + data.data.characterVal[i] + ')').prop({selected: true});
    }
    $('#characteristic-field').multiselect({
        maxHeight: 200,
        includeSelectAllOption: true,
        nonSelectedText: "Укажите поле"
    });
    $('.button-controle-export').show();
}

function getUrlParams() {
    return window
            .location
            .search
            .replace('?', '')
            .split('&')
            .reduce(
                    function (p, e) {
                        var a = e.split('=');
                        p[ decodeURIComponent(a[0])] = decodeURIComponent(a[1]);
                        return p;
                    },
                    {}
            );
}

function initUpdate() {
    $.ajax({
        url: host + '/admin/import/import/load-edit-settings',
        type: 'post',
        data: $('#form-export').serialize(),
        beforeSend: function () {
            $('body').fadeTo("slow", 0.33);
        },
        success: function (res) {
            var data = JSON.parse(res);
            if (data.status !== 'error') {
                formattedConfigImport(JSON.stringify(data));
            } else {
                warning('Error', data.text, 'danger');
                $('.tag-from-file').empty();
                $('.button-controle-export').hide();
            }
        },
        complete: function () {
            $('body').fadeTo("slow", 1);
        }
    });
}

function clearErrorFields() {
    $('.error-line').each(function () {
        $(this).removeClass('error-line');
    });
}

if (action == 'edit') {
    //initUpdate();
}

$('#download-xml').on('click', function () {
    var urlXml = $('#parseshop-link').val();
    var form = $(this).parents('form');
    $.ajax({
        url: host + '/admin/import/import/load-settings',
        type: 'post',
        data: {'url': urlXml},
        beforeSend: function () {
            $('body').fadeTo("slow", 0.33);
        },
        success: function (res) {
            var data = JSON.parse(res);
            if (data.status !== 'error') {
                formattedConfigImport(JSON.stringify(data));
                warning('Succes', 'Файл успешно загружен', 'success');
            } else {
                warning('Error', data.text, 'danger');
                $('.tag-from-file').empty();
                $('.button-controle-export').hide();
            }
        },
        complete: function () {
            $('body').fadeTo("slow", 1);
        }
    });
});

$('.button-controle-export button').on('click', function (e) {
    e.preventDefault();
    var form = $(this).closest('form').serialize();
    var xhr = true;
    var button = $(this);
    clearErrorFields();
    $.each($('.requiredField'), function () {
        if ($(this).val() === '') {
            xhr = false;
        }
    });
    if (!$('#parseshop-name').val()) {
        xhr = false;
    }
    if (!$('#parseshop-currency_value').val()) {
        xhr = false;
    }
    if (xhr) {
        $.ajax({
            url: '/admin/import/save-import/check-valid-fields',
            type: 'post',
            data: form,
            success: function (res) {
                var data = JSON.parse(res);
                if (data.status == 'success') {
                    button.submit();
                } else {
                    if (data.field != 'none') {
                        $('select[name="requiredField[' + data.field + ']"]').addClass('error-line');
                        $('select[name="additionalField[' + data.field + ']"]').addClass('error-line');
                    }
                    warning('Error', data.message, 'danger');
                }
            },
        });
    } else {
        warning('Warning', 'Заполните основные поля, а так же поля атрибутов магазина');
    }
    return false;
});

$('#parseshop-link').on('focusin', function () {
    first_state_link = $(this).val().replace(/\s/g, "");
});

$('#parseshop-link').on('focusout', function () {
    var second_state_link = $(this).val().replace(/\s/g, "");
    if (first_state_link !== '') {
        if (first_state_link !== second_state_link) {
            var result = confirm('Загрузить товары с новой ссылки?');
            if (result) {
                $('#download-xml').trigger('click');
            } else {
                $(this).val('');
            }
        }
    }
    return false;
});    