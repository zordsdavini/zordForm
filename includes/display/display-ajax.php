<?php
require_once(dirname(__FILE__) . '/../../../../../wp-load.php');

/**
 * calculate_string 
 * from http://www.website55.com/php-mysql/2010/04/how-to-calculate-strings-with-php.html
 * 
 * @param mixed $mathString 
 * @access public
 * @return void
 */
function calculate_string( $mathString )    {
    $mathString = trim($mathString);     // trim white spaces
    $mathString = ereg_replace ('[^0-9\+-\*\/\(\) ]', '', $mathString);    // remove any non-numbers chars; exception for math operators
 
    $compute = create_function("", "return (" . $mathString . ");" );
    return 0 + $compute();
}


add_action('wp_ajax_nopriv_zordform_calculate_form', 'zordform_calculate_form_callback');
add_action('wp_ajax_zordform_calculate_form', 'zordform_calculate_form_callback');
function zordform_calculate_form_callback() {
    $form_id = $_REQUEST['form_id'];
    $form = zordform_get_form_by_id($form_id);
    if ($form) {
        $fields = zordform_get_fields_by_form_id($form_id);
        $formula = $form['formula'];

        // create result
        $result_id = zordform_create_result($form_id);
        foreach ($fields as $field) {
            $name = $field['name'];
            $value = !empty($_REQUEST[$name]) ? $_REQUEST[$name] : '0';
            $formula = str_replace('[['.$name.']]', $value, $formula);

            // save data
            $data = array(
                'result_id' => $result_id,
                'field_id' => $field['id'],
                'value' => $value,
            );
            zordform_create_result_value($data);
        }

        // calculate
        $result = calculate_string($formula);
        $data = array(
            'result' => $result
        );
        zordform_update_result($result_id, $data);

        echo $result;
    }

	die();
}

?>
