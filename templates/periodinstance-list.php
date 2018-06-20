<?php if ( ! is_pseudo() ) { ?>
<tr id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
		// TODO: move explicit fields in to the template
		the_content();
	?>
</tr>
<?php } ?>
