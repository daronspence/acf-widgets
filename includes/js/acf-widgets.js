function acfw(){
	var el = jQuery("div[id*='_acf_widget_'] .acf-field");
	var num = 0;
	var body = jQuery('body');
	num = el.length;
	if ( num > 0 ){
		var remove = el.siblings('.acfw-no-acf');
		remove.attr('data-display', 'none');
	}
}
function acfw_remove_fields(){ 
	jQuery('.widget .acf-field').remove(); 
}
jQuery(document).ready( acfw );