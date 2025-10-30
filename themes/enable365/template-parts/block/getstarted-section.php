<?php
/**
 * Block Name: Staff card
 *
 * This is the template that displays the Intro-section block.
 */
?>

<?php 

$contact_title = get_field('getstarted_contact_title');
$staff_name = get_field('staff_name');
$staff_title = get_field('staff_title');
$staff_email = get_field('staff_email');
$staff_tel = get_field('staff_tel');
$staff_image = get_field('staff_img');

$getstarted_title = get_field('getstarted_title');
$getstarted_text = get_field('getstarted_text');
$getstarted_link = get_field('getstarted_button_link');
$getstarted_button = get_field('getstarted_button_text');

?>

<div class="getstarted-block">
	<div>
		<h4 class="title"><?php echo $getstarted_title; ?></h4>
		<div class="text"><?php echo $getstarted_text; ?></div>
		<a class="button" href="<?php echo $getstarted_link; ?>"><?php echo $getstarted_button; ?></a>
	</div>
	<div class="staff-card">
		<h4 class="title"><?php echo $contact_title; ?></h4>
		<figure>
			<img class="photo" src="<?php echo $staff_image['url'];?>" alt="<?php echo $staff_image['alt']; ?>">
			<figcaption>
				<div>
					<p><strong><?php echo $staff_name; ?></strong></p>
					<p><?php echo $staff_title; ?></p>
				</div>
				<div>
					<a><?php echo $staff_email; ?></a>
					<a><?php echo $staff_tel; ?></a>
				</div>
			</figcaption>
		</figure>
	</div>
	
</div>