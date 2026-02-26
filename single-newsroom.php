<?php
	get_header();
	if ( have_posts() ) :
	the_post();
?>


<main class="container container-l">
	<ul class="categories-list">
		<li>
			<a href="<?php the_permalink();?>/newsroom">Back to newsroom</a>
		</li>
	</ul>
	<?php the_title();?>
	<?php the_content();?>
</main>

<?php endif; ?>

<?php
	get_footer();
?>