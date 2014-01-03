<?php
require_once(dirname(__FILE__) . '/../../../../../wp-load.php');

add_action( 'admin_enqueue_scripts', 'zordform_enqueue' );
function zordform_enqueue($hook) {
    if( 'toplevel_page_zordform' != $hook ) return;	// Only applies to zordform
        
	wp_enqueue_script( 'ajax-script', plugins_url( ZORDFORM_URL .'/js/zordform-admin.js', __FILE__ ), array('jquery'));

	// in javascript, object properties are accessed as ajax_object.ajax_url
	wp_localize_script( 'ajax-script', 'ajax_object',
        array( 'ajax_url' => admin_url( 'admin-ajax.php' ),
        'label_are_delete_form' => __('Are you sure want to delete form?', 'zordform'),
        'label_smth_wrong' => __('Something wrong :-(', 'zordform'),
        'label_are_delete_field' => __('Are you sure want to delete field. Related data will be lost.', 'zordform')
    ));
}

add_action('wp_ajax_zordform_add_field', 'zordform_add_field_callback');
add_action('wp_ajax_nopriv_zordform_add_field', 'zordform_add_field_callback');
function zordform_add_field_callback() {
    $randomStr = substr(md5(rand()),0,7);
    echo '<div ref="new_'.$randomStr.'">
        <table class="field" width="100%">
            <tr> 
                <th>Name</th>
                <td><input name="field[new_'.$randomStr.'][name]" value="'.$field['name'].'" required /></td>
            </tr>
            <tr> 
                <th>Type</th>
                <td><select name="field[new_'.$randomStr.'][type]" required>
                    <option value="float">Float</option>
                    <option value="int">Int</option>
                    </select>
                </td>
            </tr>
            <tr> 
                <th>Default value</th>
                <td>
                    <input name="field[new_'.$randomStr.'][setted]" value="'.$field['setted'].'" required />
                    <span class="remove-field button" ref="new_'.$randomStr.'">Remove field</span>
                </td>
            </tr>
        </table><hr /></div>';
	die();
}

add_action('wp_ajax_zordform_delete_form', 'zordform_delete_form_callback');
add_action('wp_ajax_nopriv_zordform_delete_form', 'zordform_delete_form_callback');
function zordform_delete_form_callback() {
    $form_id = $_POST['form_id'];
    if ($form_id && is_admin()) {
        $result = zordform_delete_form($form_id);

        if ($result) {
            echo 'OK';
        }
    }

	die();
}

?>
