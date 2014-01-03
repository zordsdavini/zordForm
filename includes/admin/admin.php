<?php

// all pages options
$views = array(
    'list' => array(
        'title' => 'Zord Forms',
        'links' => array(
            'admin.php?page=zordform&view=form&form_id=new' => 'Add New',
        ),
        'callback' => 'zordform_view_list',
    ),
    'form' => array(
        'title' => 'Edit Zord Form',
        'callback' => 'zordform_view_edit',
    ),
    'results' => array(
        'title' => 'Zord Form Results',
        'callback' => 'zordform_view_results',
    ),
);

add_action( 'admin_menu', 'zordform_add_menu' );
function zordform_add_menu(){
	$plugins_url = plugins_url();

	$capabilities = 'administrator';
	$capabilities = apply_filters( 'zordform_admin_menu_capabilities', $capabilities );

	$page = add_menu_page("Zord Form" , __( 'Zord Forms', 'zordform' ), $capabilities, "zordform", "zordform_admin", ZORDFORM_URL."/images/zordsdavini-ico-small.png" );
	$all_forms = add_submenu_page("zordform", 'Zord Forms: '.__( 'All Forms', 'zordform' ), __( 'All Forms', 'zordform' ), $capabilities, "zordform", "zordform_admin");
	$new_form = add_submenu_page("zordform", __( 'Add New', 'zordform' ), __( 'Add New', 'zordform' ), $capabilities, "zordform&view=form&form_id=new", "zordform_admin");

	add_action('admin_print_styles-' . $page, 'zordform_admin_css');
	add_action('admin_print_styles-' . $page, 'zordform_admin_js');

	add_action('admin_print_styles-' . $new_form, 'zordform_admin_css');
	add_action('admin_print_styles-' . $new_form, 'zordform_admin_js');
}
function zordform_admin(){
	global $wpdb, $views, $zordform_admin_update_message;

	$current_page = $_REQUEST['page'];
    if (isset($_REQUEST['view'])) {
        $current_view = $_REQUEST['view'];
    } else {
        $current_view = 'list';
    }

	if (isset($_REQUEST['form_id'])) {
		$form_id = $_REQUEST['form_id'];
	} else {
		$form_id = '';
	}

	if( empty($zordform_admin_update_message) AND isset( $_REQUEST['update_message'] ) ){
		$zordform_admin_update_message = urldecode($_REQUEST['update_message']);
	}
	?>

    <input type="hidden" id="icon-url" value="<?php echo ZORDFORM_URL."/images/zordsdavini-ico.png" ?>" />

	<form id="zordform_admin" enctype="multipart/form-data" method="post" name="" action="">
		<input type="hidden" name="_page" id="_page" value="<?php echo $current_page;?>">
		<input type="hidden" name="_view" id="_page" value="<?php echo $current_view;?>">
		<input type="hidden" name="_form_id"  id="_form_id" value="<?php echo $form_id;?>">
		<?php wp_nonce_field('_zordform_save','_zordform_admin_submit'); ?>
		<div class="wrap">
			<?php
				screen_icon( 'zordform' );
                if (isset($views[$current_view]['title'])) { ?>
                    <h2>
                        <?php echo $views[$current_view]['title']; ?>
                        <?php if (!empty($views[$current_view]['links'])) {
                            foreach ($views[$current_view]['links'] as $href => $link) {
                                echo "<a class='add-new-h2' href=$href>".__($link, 'zordform')."</a>";
                            }
                        } ?>
                    </h2>
                <?php }

				if( isset( $zordform_admin_update_message ) AND $zordform_admin_update_message != '' ){
					?>
					<div id="message" class="updated below-h2">
						<p>
							<?php echo $zordform_admin_update_message;?>
						</p>
					</div>
					<?php
				}

                // write get page part
                $arguments = func_get_args();
                call_user_func_array($views[$current_view]['callback'], $arguments);
            ?>

		</div>
	<!-- </div>/.wrap-->
</form>
<?php
} //End zordfrom function

if(is_admin()){
	require_once(ABSPATH . 'wp-admin/includes/post.php');
}
