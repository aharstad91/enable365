<?php
	get_header();
	if ( have_posts() ) :
	the_post();
?>


<main class="try-now-container">
	<div class="try-now">
		<?php the_content();?>
	</section>
</main>

<?php endif; ?>

<?php
	get_footer();
?>