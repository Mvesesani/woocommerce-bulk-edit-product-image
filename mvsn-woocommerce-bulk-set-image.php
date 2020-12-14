<?php

/**
 * Plugin Name: MVSN WooCommerce Bulk Set Image
 * Description: Great for product image management in WooCommerce! This plugin adds an image selection option to the WooCommerce product bulk edit screen.
 * Version: 1.0
 * Author: Twaambo Haamucenje
 */

add_action('woocommerce_product_bulk_edit_start', 'mvsn_bulk_image_edit');

function mvsn_bulk_image_edit()
{
?>
	<h4 style="margin-bottom:0px;"><a class="mvsn-bulk-image" href="">Select an image to bulk set...</a></h4>
	<div class="inline-edit-group mvsn-bulk-image-option">
		<label class="alignleft">
			<span class="title">Bulk Image</span>
			<span class="input-text-wrap">
				<input name="_mvsn_bulk_image_id" id="mvsn-bulk-image-id" class=="mvsn-bulk-image-id" type="text">
			</span>
		</label>
	</div>
	<script type='text/javascript'>
		jQuery(document).ready(function() {
			//uploading files variable
			var custom_file_frame;

			// upload new image
			jQuery('.mvsn-bulk-image').click(function(e) {
				e.preventDefault();
				e.stopPropagation();

				//If the frame already exists, reopen it
				if (typeof(custom_file_frame) !== "undefined") {
					custom_file_frame.close();
				}

				//Create WP media frame.
				custom_file_frame = wp.media.frames.customHeader = wp.media({
					//Title of media manager frame
					title: "Set Bulk Product Image",
					library: {
						type: 'image'
					},
					button: {
						//Button text
						text: "Set Bulk Product Image"
					},
					//Do not allow multiple files, if you want multiple, set true
					multiple: false
				});
				custom_file_frame.on('select', function() {

					var attachment = custom_file_frame.state().get('selection').first().toJSON();
					console.log(attachment);

					jQuery("#mvsn-bulk-image-id").val(attachment.id);

				});
				custom_file_frame.open();
			});
		});
	</script>
<?php
}

add_action('woocommerce_product_bulk_edit_save', 'mvsn_bulk_image_save');

function mvsn_bulk_image_save($product)
{
	if (isset($_REQUEST['_mvsn_bulk_image_id'])) {
		if ($_REQUEST['_mvsn_bulk_image_id'] !== '') {
			$post_id = $product->get_id();
			$image_id = $_REQUEST['_mvsn_bulk_image_id'];
			update_post_meta($post_id, '_thumbnail_id', $image_id);
		}
	}
}
