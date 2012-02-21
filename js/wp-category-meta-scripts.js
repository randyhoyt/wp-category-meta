//Extracted from fpg_scripts.js (Flash Picture Gallery Plugin) and modified for use here.
function image_url_sync(){
	add_image_url = '';
    add_image_url = image_url_collection;
    view_image_url = "<img src=\"" + add_image_url + "\" width=\"200px\" />";
       
    if (add_image_url == '') add_image_url = 'No images selected';
    field = '';
    field = jQuery("#image_field").val();
    
    url_display_id = '#' + field + '_url_display';
    image_display_id = '#' + field + '_selected_image';
    
    jQuery(url_display_id).html(add_image_url);
	jQuery('#' + field).val(add_image_url);
	jQuery(image_display_id).html(view_image_url);
	jQuery("#image_field").val('');
    
}

function image_url_add(){
	image_url = edCanvas.value.match(/img src=\"(.*?)\"/g)[0].split(/img src=\"(.*?)\"/g)[1];
    image_url = image_url.replace(/-[0-9][0-9][0-9]x[0-9][0-9][0-9]\./i,'.');
    image_url_collection = image_url;
    edCanvas.value = '';
    image_url_sync();
}

function image_photo_url_add($field){
	jQuery("#image_field").val($field);
}

function remove_image_url($field, $message){
	url_display_id = '#' + $field + '_url_display';
    image_display_id = '#' + $field + '_selected_image';
    
    jQuery(url_display_id).html($message);
	jQuery('#' + $field).val('');
	jQuery(image_display_id).html('');
}
