<?php 
	get_header(); 
	the_post();
?>
	<main class="container-l">
		<?php the_content();?>
		
		<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
		<script src="<?php echo get_template_directory_uri(); ?>/template-parts/gsap-animations.js"></script>
	</main>

<?php 
	get_footer();
?>