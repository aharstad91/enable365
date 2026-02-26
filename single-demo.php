<?php
	get_header();
	if ( have_posts() ) :
	the_post();
?>


<main class="">
	<div class="iframe-container">
	<?php the_content();?>
	</div>
	</section>
</main>

<?php endif; ?>

<?php
	get_footer();
?>