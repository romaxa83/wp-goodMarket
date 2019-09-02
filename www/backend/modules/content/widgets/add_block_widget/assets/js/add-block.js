$('document').ready(function(){
    $('.add-block').on('click', function() {
        var widget = $(this).parents('.add-block-widget');
        var data = widget.find('input, select').serialize();
        var group = widget.find('input[name=content-group]').val();
        var parentContainerId = widget.find('input[name=content-parent]').val();
        var tabId = $('#' + parentContainerId).parents('.tab-pane').attr('id');

        $('.nav-tabs').find('[data-tab=' + tabId + ']').trigger('click');

        $.ajax({
            type: 'post',
            url: widget.data('route'),
            data: data,
            success: function(form){
                form = $.parseJSON(form);
                var container = $('#' + parentContainerId);
                container.append(form.html);
                if(form.type == 'editor') {
                    $('#' + group + '-' + form.id).redactor({
                        'minHeight': 200
                    });
                }
                container.animate({
                    scrollTop: $('.new-block').last().offset().top
                }, 500);
            }
        });
    });
});