<?php
	get_header();
	if ( have_posts() ) :
	the_post();
?>
	<main class="container-l">
		<?php the_content();?>
		<!-- GSAP scripts are now enqueued properly via functions.php for WP Fastest Cache optimization -->
	</main>
<?php endif; ?>

<?php
	get_footer();
?>