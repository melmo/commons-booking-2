
		<div id="tabs-2" class="wrap">
			<?php
						$cmb = new_cmb2_box( array(
				'id' => CB_TEXTDOMAIN . '_options-second',
				'hookup' => false,
				'show_on' => array( 'key' => 'options-page', 'value' => array( CB_TEXTDOMAIN ), ),
				'show_names' => true,
					) );
			$cmb->add_field( array(
				'name' => __( 'Text', CB_TEXTDOMAIN ),
				'desc' => __( 'field description (optional)', CB_TEXTDOMAIN ),
				'id' => '_text-second',
				'type' => 'text',
				'default' => 'Default Text',
			) );
			$cmb->add_field( array(
				'name' => __( 'Color Picker', CB_TEXTDOMAIN ),
				'desc' => __( 'field description (optional)', CB_TEXTDOMAIN ),
				'id' => '_colorpicker-second',
				'type' => 'colorpicker',
				'default' => '#bada55',
			) );
			cmb2_metabox_form( CB_TEXTDOMAIN . '_options-second', CB_TEXTDOMAIN . '-settings-second' );
						?>
			<!-- @TODO: Provide other markup for your options page here. -->
		</div>
