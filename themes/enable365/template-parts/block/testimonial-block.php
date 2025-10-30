<?php
/**
 * Block Name: Testimonial
 *
 * This is the template that displays the Intro-section block.
 */
?>

<?php 

$logo = get_field('testimonial_logo');
$text = get_field('testimonial_text');
$avatar = get_field('testimonial_avatar');
$person = get_field('testimonial_person');
$title = get_field('testimonial_title');



?>

<div class="testimonial">
	<div class="medium-wrap">
		<figure class="flex justify-center mb-6">
			<img class="logo" src="<?php echo $logo['url'];?>" alt="<?php echo $logo['alt']; ?>">
		</figure>
		<div class="text text-center"><?php echo $text; ?></div>
		<figure class="flex justify-center gap-4">
			<img class="avatar" src="<?php echo $avatar['url'];?>" alt="<?php echo $avatar['alt']; ?>">
			<figcaption>
				<p><strong><?php echo $person; ?></strong></p>
				<p><?php echo $title; ?></p>
			</figcaption>
		</figure>
	</div>
</div>