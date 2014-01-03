<?php

function zordform_admin_save(){
	global $zordform_admin_update_message;
    $zordform_admin_update_message = '';
	if(!empty($_POST) AND !empty($_POST['_zordform_admin_submit']) AND $_POST['_zordform_admin_submit'] != ''){
		if(wp_verify_nonce($_POST['_zordform_admin_submit'],'_zordform_save') AND check_admin_referer('_zordform_save','_zordform_admin_submit')){
			$data_array = array();
			if ( isset( $_REQUEST['form_id'] ) && $_REQUEST['form_id'] != 'new') {
				$form_id = $_REQUEST['form_id'];
			} else {
				$form_id = '';
			}
			foreach ( $_POST as $key => $val ) {
				if ( substr($key, 0, 1) != '_') {
					$data_array[$key] = $val;
				}
			}

            $data = array(
                'name'    => $data_array['name'],
                'formula' => $data_array['formula'],
                'comment' => $data_array['comment']
            );

            if ($form_id) {
                $result = zordform_update_form($form_id, $data);
                if ($result) {
                    $zordform_admin_update_message = 'Form is updated';
                } else {
                    $zordform_admin_update_message = 'Form update failed :(';
                }
                $url = '/wp-admin/admin.php?page=zordform&view=form&form_id='.$form_id;
            } else {
                $result = zordform_create_form($data);
                if ($result) {
                    $form_id = $result;
                    $zordform_admin_update_message = 'Form is created';
                    $url = '/wp-admin/admin.php?page=zordform&view=form&form_id='.$form_id;
                } else {
                    $zordform_admin_update_message = 'To create form failed :(';
                    $url = '/wp-admin/admin.php?page=zordform&view=form&form_id=new';
                }
            }

            ////// saving fields
            if ($form_id) {
                $old_fields = zordform_get_fields_by_form_id($form_id);

                $fields = $_POST['field'];
                foreach ($fields as $key => $field) {
                    $data = array(
                        'name' => $field['name'],
                        'type' => $field['type'],
                        'setted' => $field['setted']
                    );

                    // check with existing one, if exist - update else create
                    if (preg_match('/^\d+$/', $key)) {
                        foreach ($old_fields as $i => $old_field) {
                            if ($old_field['id'] == $key) {
                                zordform_update_field($key, $data);
                                unset($old_fields[$i]);            
                            }
                        }
                    } else {
                        $data['form_id'] = $form_id;
                        zordform_create_field($data);
                    }
                }

                // remove deleted fields
                foreach ($old_fields as $old_field) {
                    zordform_delete_field($old_field['id']);
                }
            }

            wp_redirect( $url.'&update_message='.urlencode($zordform_admin_update_message) );
		}
	}
}

add_action( 'admin_init', 'zordform_admin_save', 999 );
