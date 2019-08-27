var manage_table = $('#actions-table');
var table = $('#permissions-actions-table');

const select_names = ['module', 'submodule', 'controller', 'action'];

$('#manage-actions-table select').on('select2:select', function (e) {
    var data = e.params.data;
    var element = data.text;
    var element_id = data.id;
    var name = $(this).attr('name');
    var parent_route = $(this).attr('data-parent-route');
    var route = parent_route + '/' + element;
    return $.ajax({
        type: 'POST',
        url: host + '/admin/users/roles/permission/ajax-get-route',
        dataType: 'json',
        data: {'route':route},
        success: function (data) {
            parent_route = '';
            for (var list_name in data) {
                select = $('select[name = "' + list_name + '"]');
                if(name == list_name){
                    val = element_id;
                }else{
                    val = select.val();
                }
                if((val === '') || (data[list_name][val] === undefined)){val = 0;}
                fillSelect(select, data[list_name], parent_route, val);
                parent_route += '/' + ((data[list_name][val] === undefined) ? 'none' : data[list_name][val]);
            }
        }
    });
});

$('body').on('click', '.save-route', function(e){
    var select = $('select[name = "action"]');
    var route_one = select.attr('data-route');
    if(route_one !== ''){
        var routes = getRoutes();
        routes[routes.length] = route_one;
        reloadRoutesTable(routes);
        setRoutes(routes);
    }
});

$('body').on('click', '.delete-permission-action', function(e){
    var index = $(this).data('index');
    var routes = getRoutes();
    routes.splice(index, 1);
    reloadRoutesTable(routes);
    setRoutes(routes);
});

function fillSelect(select, data, parent_route, value = 0){
    select.empty();
    if(data != false){
        for (var i in data) {
            var item = new Option(data[i], i, false, false);
            select.append(item);
        }
    }else{
        select.append(new Option('Ничео не найдено', 0, false, false));
    }
    select.removeAttr('disabled');
    select.val(value).trigger('change');
    select.attr('data-parent-route', parent_route);
    select.attr('data-route', parent_route +'/'+data[val]);
}

function clearForm(){
    $('select[name = "' + select_names[0] + '"]').val(null).trigger('change');
    for (var i = 1; i < select_names.length; i++) {
        var name = select_names[i];
        $('select[name = "' + name + '"]').val(null).empty().trigger('change');
        $('select[name = "' + name + '"]').attr('data-parent-route', '');
        $('select[name = "' + name + '"]').attr('data-route', '');
    }
}

function getKeyByValue(object, value) {
    return Object.keys(object).find(key => object[key] === value);
}

function getRoutes(){
    console.log($('.actions-table').find('input[name="routes"]').val());
   return Array.from(JSON.parse($('.actions-table').find('input[name="routes"]').val()));
}

function setRoutes(data){
    $('.actions-table').find('input[name="routes"]').val(JSON.stringify(getUnique(data)));
}

function reloadRoutesTable(data){
    clearForm();
    $.ajax({
        type: 'POST',
        url:  host + '/admin/users/roles/permission/ajax-load-routes-table',
        data: {'routes':data},
        success: function (html) {
            $('#permissions-actions-table').parent().html(html);
        }
    });
}

function getUnique(arr){
    var unique = [];
    $.each(arr, function(i, el){
        if($.inArray(el, unique) === -1) unique.push(el);
    });
    return unique;
}