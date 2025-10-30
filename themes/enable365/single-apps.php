<?php 
	get_header(); 
	the_post();
?>


<main class="container container-l">
	<section class="">
		<?php the_content();?>
	</section>
</main>


<?php 
	get_footer();
?>