<?php 
	get_header(); 
	the_post();
?>
	<main class="container-l">
		<?php the_content();?>
		<!-- GSAP scripts are now enqueued properly via functions.php for WP Fastest Cache optimization -->
	</main>

<?php 
	get_footer();
?>