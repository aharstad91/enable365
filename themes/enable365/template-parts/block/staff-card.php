<?php
/**
 * Block Name: Staff card
 *
 * This is the template that displays the Intro-section block.
 */
?>

<?php 

$name = get_field('staff_name');
$title = get_field('staff_title');
$email = get_field('staff_email');
$tel = get_field('staff_tel');
$image = get_field('staff_img');



?>

<div class="staff-block">
	<figure>
		<img class="photo" src="<?php echo $image['url'];?>" alt="<?php echo $image['alt']; ?>">
		<figcaption>
			<div>
				<p><strong><?php echo $name; ?></strong></p>
				<p><?php echo $title; ?></p>
			</div>
			<div>
				<a><?php echo $email; ?></a>
				<a><?php echo $tel; ?></a>
				</div>
		</figcaption>
	</figure>

</div>