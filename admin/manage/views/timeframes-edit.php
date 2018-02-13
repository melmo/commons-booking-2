<?php
/**
 * Template for the timeframes edit screen.
 *
 *
 * @package   Commons_Booking
 * @author    Florian Egermann <florian@wielebenwir.de>
 * @copyright 2018 wielebenwir e.V.
 * @license   GPL 2.0+
 * @link      http://commonsbooking.wielebenwir.de
 */
/**
 * Interface for adding/editing timeframes.
 */
?>
<?php

		$Timeframes_Edit = new CB_Timeframes_Edit();

		$defaults = $Timeframes_Edit->default_fields;
		$Timeframes_Edit->set_basename( basename(__FILE__) );
		$Timeframes_Edit->handle_request( $_REQUEST ); // handle adding and updating

		$edit_slug = $Timeframes_Edit->edit_slug; // set the slug from CB_Admin_Enque

    // this is default $item which will be used for new records
		$default = $Timeframes_Edit->default_fields;

		// if this is not post back we load item to edit or give new one to create
		$item_id = $Timeframes_Edit->get_timeframe_id_from_request( $_REQUEST );
    $item = $Timeframes_Edit->get_single_timeframe( $item_id );

		add_meta_box('timeframe_form_meta_box', __('Timeframe settings', 'commons-booking') , 'render_timeframe_meta_box' , 'timeframe', 'normal', 'default');
		if( is_array($item) ) { // make sure that id exists
			// here we adding our custom meta box
		} else {
			echo ( __('Timeframe not found', 'commons-booking' ) );
		}
		// var_dump($item);

    ?>
<div class="wrap">
    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2><?php _e('Timeframe', 'commons-booking')?> <a class="add-new-h2" href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=cb_timeframes_table' );?>"><?php _e('back to list', 'commons-booking')?></a>
    </h2>

    <form id="form" method="POST">
        <input type="hidden" name="nonce" value="<?php echo wp_create_nonce(basename(__FILE__))?>"/>
        <?php /* NOTICE: here we storing id to determine will be item added or updated */ ?>
        <input type="hidden" name="timeframe_id" value="<?php echo $item['timeframe_id'] ?>"/>

        <div class="metabox-holder" id="poststuff">
            <div id="post-body">
                <div id="post-body-content">
                    <?php /* And here we call our custom meta box */ ?>
                    <?php do_meta_boxes('timeframe', 'normal', $item); ?>
                    <input type="submit" value="<?php _e('Save', 'commons-booking')?>" id="submit" class="button-primary" name="submit">
                </div>
            </div>
        </div>
    </form>
</div>

<?php
// function to render the actual metabox contents
function render_timeframe_meta_box( $item ) {

	$Timeframes_Edit = new CB_Timeframes_Edit; // we need the class to format out entries.

	?>
<table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
    <tbody>
		 <tr class="form-field cb-form-info">
        <td valign="top" colspan="4">
					<?php
					//@TODO: only if id != 0
					printf( __( 'Slots: %d total, %d booked, %d available ', 'commons-booking'),
					$item['availability']['total'],
					$item['availability']['booked'],
					$item['availability']['available']
					); ?>
        </td>
    </tr>
		 <tr class="form-field">
		 		 <tr class="form-field-group-header">
        <td colspan="4"><?php _e('General settings', 'commons-booking'); ?></td>
		</tr>
        <td valign="top">
            <label for="item_id"><?php _e('Item', 'commons-booking')?></label>
        </td>
				<td>
					<?php echo CB_Gui::cb_edit_table_post_select_html('cb_item', 'item_id', $item['item_id'] ); ?>
				</td>
        <td>
            <label for="location_id"><?php _e('Location', 'commons-booking')?></label>
        </td>
        <td>
					<?php echo CB_Gui::cb_edit_table_post_select_html('cb_location', 'location_id', $item['location_id'] ); ?>
        </td>
    </tr>
		 <tr class="form-field">
        <td valign="top">
            <label for="date_start"><?php _e('Start Date', 'commons-booking')?></label>
        </td>
        <td>
 						<input id="date_start" name="date_start" type="date" value="<?php echo esc_attr($item['date_start'])?>" class="date">
        </td>
        <td valign="top">
            <label for="date_end"><?php _e('End Date', 'commons-booking')?></label>
        </td>
        <td>
 						<input id="date_end" name="date_end" type="date" value="<?php echo esc_attr($item['date_end'])?>" class="date">
        </td>
    </tr>
		 <tr class="form-field-group-header">
        <td colspan="4"><?php _e('Exclude days', 'commons-booking'); ?></td>
		</tr>
		 <tr class="form-field">
        <td valign="top" colspan="2">
						<input id="exclude_location_closed" name="exclude_location_closed" type="checkbox" value="<?php // echo ($item['exclude_location_closed'])?>" class="checkbox">
            <label for="exclude_location_closed"><?php _e('Exclude closed days of the location', 'commons-booking')?> (Edit <?php echo $Timeframes_Edit->col_format_post($item['location_id']); ?>)</label>
						<?php //@TODO: REfresh location name & id via javascript/ajax if location_id select changes ?>
        </td>
        <td valign="top" colspan="2">
						<input id="exclude_holiday_closed" name="exclude_holiday_closed" type="checkbox" value="<?php // echo ($item['exclude_holiday_closed'])?>" class="checkbox">
            <label for="exclude_holiday_closed"><?php _e('Exclude holidays', 'commons-booking')?> (Edit holiday setting)</label>
        </td>
    </tr>
		 <tr class="form-field-group-header">
        <td colspan="4"><?php _e('Meta', 'commons-booking'); ?></td>
		</tr>
		 <tr class="form-field">
        <td valign="top">
            <label for="description"><?php _e('Description', 'commons-booking')?></label>
        </td>
        <td>
 						<input id="description" name="description" type="text" value="<?php echo esc_attr($item['description'])?>" class="description">
        </td>
        <td valign="top">
            <label for="owner_id"><?php _e('Owner', 'commons-booking')?></label>
        </td>
        <td>
				<?php echo CB_Gui::cb_edit_table_owner_select_html( array('subscriber', 'editor', 'admin'), $item['owner_id'] ); ?>
        </td>
    </tr>
    </tbody>
</table>
<?php } ?>
