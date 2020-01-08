(function( $ ) {
	'use strict';

	var max_fields      = 10; //maximum input boxes allowed
	var x = 1; //initlal text box count

	$(function(){

		$('.data_columns_wrap').on('click', '.add_data_columns_button', function(e){
			e.preventDefault();
			if(x < max_fields){ //max input box allowed
				x++; //text box increment
				$('.data_columns_wrap').append('<div><textarea name="_c3d_data_columns[]" style="width:90%"></textarea> <a href="javascript:void(0);" class="remove_field button-secondary">－</a></div>'); //add input box
			}
		});
		$('.data_columns_wrap').on('click', '.remove_field', function(e){ //user click on remove text
			e.preventDefault(); 
			$(this).parent('div').remove(); x--;
		});

		$('.grid_x_lines_wrap').on('click', '.add_grid_x_lines_button', function(e){
			e.preventDefault();
			if(x < max_fields){ //max input box allowed
				x++; //text box increment
				$('.grid_x_lines_wrap').append('<div><input type="text" name="_c3d_grid_x_lines[]" style="width:90%" /> <a href="javascript:void(0);" class="remove_field button-secondary">－</a></div>'); //add input box
			}
		});
		$('.grid_x_lines_wrap').on('click', '.remove_field', function(e){ //user click on remove text
			e.preventDefault(); 
			$(this).parent('div').remove(); x--;
		});

		$('.grid_y_lines_wrap').on('click', '.add_grid_y_lines_button', function(e){
			e.preventDefault();
			if(x < max_fields){ //max input box allowed
				x++; //text box increment
				$('.grid_y_lines_wrap').append('<div><input type="text" name="_c3d_grid_y_lines[]" style="width:90%" /> <a href="javascript:void(0);" class="remove_field button-secondary">－</a></div>'); //add input box
			}
		});
		$('.grid_y_lines_wrap').on('click', '.remove_field', function(e){ //user click on remove text
			e.preventDefault(); 
			$(this).parent('div').remove(); x--;
		});
	});

})( jQuery );
