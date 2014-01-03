<?php
function zordform_get_all_forms() {
	global $wpdb;
	$form_results = $wpdb->get_results("SELECT * FROM ".ZORDFORM_TABLE_NAME, ARRAY_A);

	return $form_results;
}

function zordform_get_form_by_id($form_id) {
	global $wpdb;
	$form_row = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".ZORDFORM_TABLE_NAME." WHERE id = %d", $form_id), ARRAY_A);

	return $form_row;
}

function zordform_get_form_by_name($name) {
	global $wpdb;
	$form_results = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".ZORDFORM_TABLE_NAME." WHERE name = %s", $name), ARRAY_A);

	return $form_results;
}

function zordform_get_fields_by_form_id($form_id) {
	global $wpdb;
	$form_results = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".ZORDFORM_FIELDS_TABLE_NAME." WHERE form_id = %d", $form_id), ARRAY_A);

	return $form_results;
}

function zordform_get_field_by_name_and_form($name, $form_id) {
	global $wpdb;
	$form_results = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".ZORDFORM_FIELDS_TABLE_NAME." WHERE name = %s AND form_id = %d", $name, $form_id), ARRAY_A);

	return $form_results;
}

function zordform_update_form($form_id, $data) {
    global $wpdb;
	$result = $wpdb->update(ZORDFORM_TABLE_NAME, $data, array('id' => $form_id));

    return $result;
}

function zordform_create_form($data) {
    global $wpdb;
    $result = $wpdb->insert(ZORDFORM_TABLE_NAME, $data);
    if (!$result) {
        return $result;
    } else {
        return $wpdb->insert_id;
    }
}

function zordform_delete_form($form_id) {
    global $wpdb;
	$result = $wpdb->delete(ZORDFORM_TABLE_NAME, array('id' => $form_id));
    if ($result) {
        $result = $wpdb->delete(ZORDFORM_FIELDS_TABLE_NAME, array('form_id' => $form_id));
    }

    return $result;
}

function zordform_update_field($field_id, $data) {
    global $wpdb;
	$result = $wpdb->update(ZORDFORM_FIELDS_TABLE_NAME, $data, array('id' => $field_id));

    return $result;
}

function zordform_delete_field($field_id) {
    global $wpdb;
	$result = $wpdb->delete(ZORDFORM_FIELDS_TABLE_NAME, array('id' => $field_id));

    return $result;
}

/**
 * zordform_create_field 
 * 
 * @param array $data array of fields
 *                      - form_id
 *                      - name
 *                      - type (int/float)
 *                      - setted (default value)
 *
 * @access public
 * @return void
 */
function zordform_create_field($data) {
    global $wpdb;
    $result = $wpdb->insert(ZORDFORM_FIELDS_TABLE_NAME, $data);
    if (!$result) {
        return $result;
    } else {
        return $wpdb->insert_id;
    }
}

function zordform_create_result($form_id) {
    global $wpdb;

    $data = array(
        'form_id' => $form_id,
        'ip' => $_SERVER["REMOTE_ADDR"],
        'result' => 0
    );
    $result = $wpdb->insert(ZORDFORM_RESULTS_TABLE_NAME, $data);
    if (!$result) {
        return $result;
    } else {
        return $wpdb->insert_id;
    }
}

function zordform_update_result($result_id, $data) {
    global $wpdb;
	$result = $wpdb->update(ZORDFORM_RESULTS_TABLE_NAME, $data, array('id' => $result_id));

    return $result;
}

/**
 * zordform_create_result_value 
 * 
 * @param array $data array of fields:
 *                      - result_id
 *                      - field_id
 *                      - value
 * @access public
 * @return void
 */
function zordform_create_result_value($data) {
    global $wpdb;
    $result = $wpdb->insert(ZORDFORM_RESULT_VALUES_TABLE_NAME, $data);
    if (!$result) {
        return $result;
    } else {
        return $wpdb->insert_id;
    }
}

function zordform_get_results_by_form_id($form_id) {
    global $wpdb;

    $fields = zordform_get_fields_by_form_id($form_id);
    $sql = 'SELECT r.id, r.result, ';
    foreach ($fields as $field) {
        $sql .= 'v'.$field['id'].'.value as '.$field['name'].', ';
    }
    $sql .= 'r.date, r.ip FROM '.ZORDFORM_RESULTS_TABLE_NAME.' r ';
    foreach ($fields as $field) {
        $sql .= 'LEFT JOIN '.ZORDFORM_RESULT_VALUES_TABLE_NAME.' v'.$field['id'].' ON v'.$field['id'].'.result_id=r.id AND v'.$field['id'].'.field_id='.$field['id'].' ';
    }
    $sql .= 'WHERE r.form_id=%d';

	$results = $wpdb->get_results($wpdb->prepare($sql, $form_id), ARRAY_A);

	return $results;
}

