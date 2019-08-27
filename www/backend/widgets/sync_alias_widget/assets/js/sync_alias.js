
$(document).ready(function(){
	var module_name;
	var field;
	init();

	function init(){
		var button = $('.generate-alias');
		var name = button.data('recipient');
		var form_id = button.data('form_id');
		var form = button.parents('form');
		// var tab = $('.language-tab-box').find('.tab-pane.active');
		// var lang =  tab.attr('id').split('_')[1];
		//field_name = module_name+'[Language]['+lang+']['+name+']';
		field = form.find('input[name="'+name+'"]');
		field.after(button);
		button.parents('.form-group').css('position','relative');
	}

	$('body').on('click','.generate-alias',function(){
		var donor = $(this).data('donor');
		var title = $(this).parents('form').find('input[name="'+donor+'"]').val();
	    $.ajax({
            type: 'POST',
            url:  host + '/admin/site/ajax-generate-alias',
            data:{title:title}, 
            success: function (alias){
                field.val(alias);
            }
        });
	});
});
