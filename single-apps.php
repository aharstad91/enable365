<?php
	get_header();
	if ( have_posts() ) :
	the_post();
?>


<main class="container container-l">
	<section class="">
		<?php the_content();?>
	</section>
</main>

<?php endif; ?>

<?php
	get_footer();
?>