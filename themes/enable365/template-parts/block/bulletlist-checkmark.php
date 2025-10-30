<?php
/**
 * Block Name: Bulletlist checkmark block
 *
 * This is the template that displays the Intro-section block.
 */
?>

<?php 
$title = get_field('bullet_list_item_title');
$text = get_field('bullet_list_item_text');

?>

<div class="bullet-list-item-with-checkmark">
	<aside><img class="" alt="" src="<?php bloginfo('template_directory'); ?>/assets/gfx/check-red.svg"></aside>
	<div>
		<div class="title font-semibold mb-2"><?php echo $title?></div>
		<p><?php echo $text?></p>
	</div>
</div>