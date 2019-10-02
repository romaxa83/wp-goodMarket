$(document).ready(function(){
    $('#choose-template').on('change', function() {
        var template = $(this).val();
        $.ajax({
            type: "post",
            url: $('input[name=slag-action]').val(),
            data: {
                template: template
            },
            success: function(route) {
                $('input[name="slug[route]"]').val(route);
            }
        })
    });
});