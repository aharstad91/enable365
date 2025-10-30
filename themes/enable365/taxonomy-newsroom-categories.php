<?php 
	get_header(); 
	the_post();
?>
<?php   
// Get CPT name
$post_type = get_post_type( $post->ID );

// Get term object
$postTerms = get_queried_object();
// Get name of term

$postTermsSlug = get_queried_object()->slug;

// https://www.wpbeginner.com/wp-themes/how-to-show-the-current-taxonomy-title-url-and-more-in-wordpress/


?>

<main class="container container-xl">
	<div>		
		<h1><?php echo $postTerms->name; ?></h1>
		<!--<p><?php echo $postTerms->description; ?></p>-->
	</div>
	
	<ul class="categories-list">
		<li>
			<a href="<?php the_permalink();?>/newsroom">Back to newsroom</a>
		</li>
	</ul>
	
	<div class="grid-tests">
		<?php
		$argstwo = array(
 		'post_type' => 'newsroom',
 		'posts_per_page' => 6,
 			'tax_query' => array(
				array(
	 			'taxonomy' => 'newsroom-categories',
	 			'field' => 'slug',
				'terms' => $postTermsSlug,
  				),
 			),
		);
		$newstwo = new WP_Query( $argstwo );
		while ( $newstwo->have_posts() ) : $newstwo->the_post();?>
			
			<div class="news-post">
				<?php if ( has_post_thumbnail() ) : ?>
					<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
						<?php the_post_thumbnail(); ?>
					</a>
				<?php endif; ?>			
				<a class="m-b-1 block" href="<?php the_permalink(); ?>"><h2><?php the_title();?></h2></a>
				<div><?php the_date( 'j F Y', '<span>', '</span>' ); ?></div>	
			</div>
		<?php endwhile; ?>
		
	</div>
</main>


<?php 
	get_footer();
?>