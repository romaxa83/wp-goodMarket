var lang_table = $('#lang');
var currency_table = $('#currency');
var sub_table;
var inputes;

$(document).mouseup(function (e) {
    if (sub_table != null) {
        if (!$(sub_table).is(e.target) && $(sub_table).has(e.target).length === 0) {
            var form = $(sub_table).parents('form');
            var save_button = $(sub_table).find('.save-lang');
            var row = $(sub_table).parents('tr[data-key]');
            var index = row.attr('data-key');
            $(form).find('input').each(function () {
                if (this.type == 'text') {
                    td_parent = $(this).parent().parent();
                    var value = inputes[this.name];
                    if (typeof value != "undefined") {
                        td_parent.html(value);
                    } else {
                        row.remove();
                        var count_row = lang_table.find('tr[data-key]').length;
                        if (count_row == 0) {
                            lang_table.find('tbody').html('<tr><td colspan="5"><div class="empty">Ничего не найдено.</div></td></tr>');
                        }
                    }
                }
            });

            save_button.replaceWith('<a class="grid-option fa fa-pencil edit-lang" href="#" title="Редактировать запись" aria-label="Редактировать запись" style="color:rgb(63,140,187)" data-action="update" data-key="' + index + '" data-pjax="1"></a> <a class="grid-option fa fa-trash delete-lang" href="#" title="Удалить запись" aria-label="Удалить запись" style="color:rgb(63,140,187)" data-confirm="Вы уверены, что хотите удалить этот элемент?" data-key="' + index + '" data-pjax="1"></a>');
        }
    }
});

$('body').on('click', '.edit-lang', function () {
    editCustomRow('/admin/settings/default/update-row-lang/', $(this), lang_table)
});

$('body').on('click', '.edit-currency', function () {
    editCustomRow('/admin/settings/default/update-row-currency/', $(this), currency_table)
});

function editCustomRow(url, edit, table) {
    var row = edit.parents('tr[data-key]');
    var index = row.attr('data-index');
    var row_id = edit.attr('data-key');
    inputes = {};
    $.ajax({
        url: host + url,
        type: 'post',
        data: {'row_id': row_id, 'index': index},
        success: function (response) {
            var col_width = [];
            $(table).find('th').each(function (i) {
                col_width[i] = $(this).outerWidth(true);
            });
            $(row).replaceWith(response);
            sub_table = $('#sub_table-' + index + ' td').addClass('table');
            sub_table.each(function (i) {
                $(this).css('width', col_width[i] + 'px');
                $(this).addClass('edit-table-lang');
            });
            var form = $(sub_table).parents('form');
            $(form).find('input').each(function () {
                inputes[this.name] = $(this).val();
            });
        }
    });
}

$('body').on('click', '.save-lang', function () {
    saveCustomRow('/admin/settings/default/save-row-lang', $(this))
});

$('body').on('click', '.save-currency', function () {
    saveCustomRow('/admin/settings/default/save-row-currency', $(this))
});

function saveCustomRow(url, save) {
    var table = save.parents('table');
    var parent_row = $(table).parents('tr');
    var record_id = save.attr('data-key');
    var action = save.attr('data-get_action');
    var form = save.parents('form');
    var entity = save.data('entity');
    var data = {};
    var error = false;
    $(form).find('input').each(function () {
        if (!$(this).val() || $(this).val() == '') {
            $(this).parent().removeClass('has-success');
            $(this).parent().addClass('has-error');
            error = true;
        } else {
            $(this).parent().removeClass('has-error');
            $(this).parent().addClass('has-success');
            data[this.name] = $(this).val();
        }
    });
    if (error == true)
        return;
    let status = $(form).find('input[name="status"]');
    if (status.length > 0) {
        data['status'] = status.is(':checked') ? 1 : 0;
    }

    $.ajax({
        url: host + url,
        type: 'post',
        data: {'action': action, 'record_id': record_id, 'edit': data},
        success: function (response)
        {
            if (response == 'ok') {
                $(form).find('input').each(function () {
                    if (this.type == 'text') {
                        td_parent = $(this).parent().parent();
                        td_parent.html($(this).val());
                    }
                });
                save.replaceWith('<a class="grid-option fa fa-pencil edit-' + entity +'" href="#" title="Редактировать запись" ' +
                    'aria-label="Редактировать запись" style="color:rgb(63,140,187)" data-action="update" data-key="' + record_id + '" ' +
                    'data-pjax="1"></a> <a class="grid-option fa fa-trash delete-' + entity +'" href="#" title="Удалить запись" aria-label="Удалить запись" ' +
                    'style="color:rgb(63,140,187)" data-confirm="Вы уверены, что хотите удалить этот элемент?" data-key="' + record_id + '" data-pjax="1"></a>');
            } else
                return;

        }
    });
}

$('.add-lang').on('click', function () {
    addCustomRow('/admin/settings/default/add-row-lang', lang_table);
});

$('.add-currency').on('click', function () {
    addCustomRow('/admin/settings/default/add-row-currency', currency_table);
});

function addCustomRow(url, table) {
    inputes = {};
    var col_width = [];
    var last_row;
    var last_index;
    var new_row;
    var index;
    var count_row_data_key = table.find('tr[data-key]').length;
    if (count_row_data_key > 0) {
        last_row = table.find('tr[data-key]:last');
        last_index = last_row.attr('data-index');
        index = parseInt(last_index) + 1;
    } else {
        index = 0;
    }
    $.ajax({
        url: host + url,
        type: 'post',
        data: {'index': index},
        success: function (response) {
            table.find('th').each(function (i) {
                col_width[i] = $(this).outerWidth(true);
            });
            if (count_row_data_key > 0) {
                last_row = table.find('tr[data-key]:last');
                table.find('tbody:first').append(response);
                new_row = $(response);
            } else {
                last_row = table.find('tr:last');
                last_row.replaceWith(response);
            }
            sub_table = $('#sub_table-' + index + ' td').addClass('table');
            sub_table.each(function (i) {
                $(this).css('width', col_width[i] + 'px');
                $(this).addClass('edit-table-lang');
            });
        }
    });
}

$('.new-setting').on('click', function () {
    inputes = {};
    var type_setting = $(this).data('type');
    var table_str = '#' + type_setting;
    var table = $(table_str);
    var col_width = [];
    var last_row;
    var last_index;
    var new_row;
    var index;
    var count_row_data_key = table.find('tr[data-key]').length;
    if (count_row_data_key > 0) {
        last_row = table.find('tr[data-key]:last');
        last_index = last_row.attr('data-index');
        index = parseInt(last_index) + 1;
    } else {
        index = 0;
    }
    $.ajax({
        url: host + '/admin/settings/default/add-row',
        type: 'post',
        data: {'index': index, 'setting': table.attr('id')},
        success: function (response)
        {
            table.find('th').each(function (i) {
                col_width[i] = $(this).outerWidth(true);
            });
            if (count_row_data_key > 0) {
                last_row = table.find('tr[data-key]:last');
                table.find('tbody:first').append(response);
                new_row = $(response);
            } else {
                last_row = table.find('tr:last');
                last_row.replaceWith(response);
            }
            sub_table = $('#sub_table-' + index + ' td').addClass('table');
            sub_table.each(function (i) {
                $(this).css('width', col_width[i] + 'px');
                $(this).addClass('edit-table-lang');
            });
        }
    });
});
$('body').on('click', '.save-row', function () {
    var save = $(this);
    var table = save.parents('table');
    var parent_row = $(table).parents('tr');
    var action = $(this).attr('data-get_action');
    var form = save.parents('form');
    var data = {};
    var error = false;
    $(form).find('input').each(function () {
        if (!$(this).val() || $(this).val() == '') {
            $(this).parent().removeClass('has-success');
            $(this).parent().addClass('has-error');
            error = true;
        } else {
            $(this).parent().removeClass('has-error');
            $(this).parent().addClass('has-success');
            data[this.name] = $(this).val();
        }
    });
    if (error == true){
        return;
    }
    data['status'] = $(form).find('input[name="status"]').is(':checked') ? 1 : 0;
    $.ajax({
        url: host + '/admin/settings/default/save-row',
        type: 'post',
        data: {'action': action, 'type': save.data('setting'), 'edit': data},
        success: function (res)
        {
            if (res) {
                var data = $.parseJSON(res);
                var table_data = {
                    key: $(form).parents('tr').attr('data-key'),
                    index: $(form).parents('tr').attr('data-index')
                };

                $(form).remove();

                $('#' + data.type).find('.number').each(function () {
                    if ($(this).text() == data.edit.position) {
                        $($(this).text(data.position_max));
                    }
                });
                $('#' + data.type).find('tbody').append(rowSettingHtml(table_data, data));
            }
        }
    });
});
function rowSettingHtml(table_data, data) {

    return '<tr background-color="white" data-key="' + table_data.key + '" data-index="' + table_data.index + '" data-id="' + data.edit.id + '" data-type="' + data.type + '">' +
            '<td>' + ++table_data.index + '</td>' +
            '<td class="text-data">' + data.edit.name + '</td>' +
            '<td class="text-data number">' + data.edit.position + '</td>' +
            '<td>' +
            '<div>' +
            '<input type="checkbox" id="cd_' + data.type + '_' + data.edit.id + '" class="tgl tgl-light publish-toggle status-toggle" name="status" value="1" checked="" data-id="' + data.edit.id + '" data-url="/admin/settings/default/update-status-' + data.type + '">' +
            '<label class="tgl-btn" for="cd_' + data.type + '_' + data.edit.id + '"></label>' +
            '</div>' +
            '</td>' +
            '</tr>';
}

$('body').on('click', '.delete-lang', function () {
    deleteCustomRow('/admin/settings/default/delete-row-lang', $(this), lang_table);
});

$('body').on('click', '.delete-currency', function () {
    deleteCustomRow('/admin/settings/default/delete-row-currency', $(this), currency_table);
});

function deleteCustomRow(url, del, table) {
    var row = del.parents('tr[data-key]');
    var row_id = del.attr('data-key');
    $.ajax({
        url: host + url,
        type: 'post',
        data: {row_id: row_id},
        success: function (data_count)
        {
            if (data_count > 0) {
                row.remove();
            } else {
                table.find('tbody').html('<tr><td colspan="5"><div class="empty">Ничего не найдено.</div></td></tr>');
            }
        }
    });
    return false;
}

var mail = /^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/;
var phone = /(\+)+(38)+(\()+([0-9]{3})+(\)-)+([0-9]{3}-)+([0-9]{2}-)+([0-9]{2})/;


$('#contact .text-data, .update-setting .text-data').on('click', function () {
    var oldValue;
    if (!$('.life-edit').is(':focus')) {
        oldValue = $(this).closest('td').attr('data-oldvalue');

        if ($(this).hasClass('number')) {
            $(this).html('<input class="life-edit" type="number" min="1" style="width:50%">');
        } else {
            $(this).html('<input class="life-edit" type="text" style="width:100%">');
        }
        $(this).append('<p class="error-message"></p>');

        if ($(this).parents('#group').length == 0) {
            $(this).append('<p class="error-message"></p>');
        }

        $(this).closest('td').attr('data-oldvalue', oldValue);
        $('.life-edit').focus().val(oldValue);
    }
    return;
});

$('#contact, .update-setting').on('blur', '.life-edit', function () {
    var xhr = true;
    var oldValue = $(this).closest('td').attr('data-oldvalue');
    var newValue = $(this).val();
    if (oldValue !== $(this).val() && newValue !== '') {
        var alias = $(this).closest('tr').attr('data-index');
        if (alias == 'mail' && mail.test(newValue) !== true) {
            xhr = false;
            $(this).closest('td').find('p').html(patternError('Введите корректный e-mail', 'Пример: test@mail.com'));
        } else if (alias == 'phone' && phone.test(newValue) !== true) {
            xhr = false;
            $(this).closest('td').find('p').html(patternError('Введите корректный номер телефона', 'Пример: +38(099)-999-99-99'));
        }
        var tr = $(this).closest('tr');
        var type = tr.attr('data-type');
        var field = $(this).attr('type');
        if (xhr == true) {
            var subString = newValue;
            if(newValue.length > 30){
                var subString = subString.substr(0,30) + '...';
            }
            tr.find('.text-data').empty().text(subString);
            tr.find('.text-data').attr('data-oldvalue', newValue);
            $.ajax({
                url: '/admin/settings/default/update',
                type: 'post',
                data: {alias: alias, body: newValue, id: tr.attr('data-id'), type: type, field: field, old_value: oldValue},
                success: function (res) {
                    if (res) {
                        var data = $.parseJSON(res);
                        $('#' + data.type).find('.' + data.field).each(function () {
                            if ($(this).text() == data.body && $(this).parent().attr('data-id') != data.id) {
                                $(this).text(data.old_value);
                            }
                        });
                    }
                    tr.find('input[type="checkbox"]').prop("disabled", false);
                }
            });
        } else {
            $(this).focus().val(newValue);
            $(this).bind('click', function () {
                return false;
            });
        }
    } else {
        if (oldValue !== 'undefined') {
            $(this).closest('td').empty().text(oldValue);
        }
    }
});
// $('#group').on('blur', '.life-edit', function () {
//     var xhr = true;
//     var oldValue = $(this).closest('td').attr('data-oldvalue');
//     var newValue = $(this).val();
//     var alias = $(this).closest('tr').attr('data-index');
//     if (alias == 'mail' && mail.test(newValue) !== true) {
//         xhr = false;
//         $(this).closest('td').find('p').html(patternError('Введите корректный e-mail', 'Пример: test@mail.com'));
//     } else if (alias == 'phone' && phone.test(newValue) !== true) {
//         xhr = false;
//         $(this).closest('td').find('p').html(patternError('Введите корректный номер телефона', 'Пример: +38(099)-999-99-99'));
//     }
//     if (xhr == true) {
//         $(this).closest('td').empty().text(newValue);
//         $(this).closest('td').attr('data-oldvalue', newValue);
//         $.ajax({
//             url: '/admin/settings/default/update',
//             type: 'post',
//             data: {alias: alias, body: newValue}
//         });
//     } else {
//         $(this).focus().val(newValue);
//         $(this).bind('click', function () {
//             return false;
//         });
//     }
// });
function patternError(text_err = ' ', text_help = ' ') {
    return  '<span class="inaccurate-input">' + text_err + '</span><br>' +
            '<span class="inaccurate-input">' + text_help + '</span>';
}

$('.coordinate').on('blur', function () {
    var data = {
        name: $(this).prop('name'),
        body: $(this).prop('value')
    };
    $.ajax({
        url: host + '/admin/settings/default/set-coordinate',
        type: 'POST',
        data: data
    });
});
