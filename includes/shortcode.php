<?php


function zordform_field_func( $atts ) {
	extract( shortcode_atts( array(
		'form' => '',
		'field' => '',
	), $atts ) );

    $forms = explode(',', $form);
    $formsObj = array();
    foreach ($forms as $form) {
        $formObj = zordform_get_form_by_name($form);
        if ($formObj) {
            $formsObj[] = $formObj;
        }
    }
    if (count($formsObj) === count($forms)) {
        $validField = true;
        foreach ($formsObj as $form) {
            $fieldObj = zordform_get_field_by_name_and_form($field, $form['id']);
            if (!$fieldObj) {
                $validField = false;
            }
        }

        if ($validField) {
            $field = $fieldObj;

            $type_map = array(
                'int' => 'text',
                'float' => 'text',
            );

            $widget = '';
            if ($type_map[$field['type']] == 'text') {
                $widget .= "<input type='text' ";
            }

            $widget .= 'name="'.$field['name'].'" class="zordform-field ';
            foreach ($formsObj as $form) {
                $widget .= ' zordform_form_'.$form['id'];
            }
            $widget .= '" ';

            if ($type_map[$field['type']] == 'text') {
                $widget .= "value='".$field['setted']."' />";
            }

            return $widget;
        }
    }

	return "==== Error getting field... ==== ";
}
add_shortcode( 'zordform_field', 'zordform_field_func' );

function zordform_result_func( $atts ) {
    // load js
	wp_enqueue_script('zordform-display', ZORDFORM_URL .'/js/zordform-display.js', array('jquery'));
	wp_localize_script( 'zordform-display', 'ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ));

	extract( shortcode_atts( array(
		'form' => '',
	), $atts ) );

    $form = zordform_get_form_by_name($form);
    if ($form) {
        return "<button class='zordform-result-button' ref='".$form['id']."'>".__("Calculate", "zordform")."</button><input disabled class='zordform-result-span zordform_result_".$form['id']."' value='0' />";
    }

	return "==== Error getting form result... ==== ";
}
add_shortcode( 'zordform_result', 'zordform_result_func' );


