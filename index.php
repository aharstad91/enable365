<?php
	get_header();
	if ( have_posts() ) :
	the_post();
?>


<main class="container container-l">
	<?php the_content();?>
</main>

<?php endif; ?>

<?php
	get_footer();
?>