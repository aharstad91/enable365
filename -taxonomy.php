<?php 
	get_header(); 
	the_post();
?>
<?php   
$taxonomy = 'newsroom-categories';
$postType = 'newsroom';

$terms = get_terms( array(
	'taxonomy' => $taxonomy,
	'orderby' => 'term_id',
	'parent' => 0,
	'hide_empty' => false,
	
	)
); ?>

<main class="container container-l newsroom-tax">
	<div>
		<h1>Presse og nyheter</h1>
		<p>Her finner du de siste nyhetene fra Enable AS og kontaktinformasjon til pressekontaktene.</p>
	</div>
	
	<?php foreach($terms as $term){ ?>
	
	<?php 	$term_link = get_term_link( $term );?>
	
	<li class="categories-list">
		<?php echo '<li><a href="' . esc_url( $term_link ) . '">' . $term->name . '</a></li>';?>
		
		<a href="<?php the_permalink();?>"><?php echo $term->name?> (<?php echo $term->count;?>)</a>
	</li>
	
	<?php } ?>    
	
	
	<?php 
	$args = array('post_type' => array('newsroom'), 'posts_per_page' => -1, 'orderby' => 'menu_order title', 'order'=>'ASC');
	?>
	<div class="grid-tests">
			<?php
			$news = new WP_Query( $args );
			if ( $news->have_posts() ) :
				while ( $news->have_posts() ) : $news->the_post();?>
				
				<div>
					<?php the_title();?>
					
					<?php if ( has_post_thumbnail() ) : ?>
						<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
							<?php the_post_thumbnail(); ?>
						</a>
					<?php endif; ?>					
				</div>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
			<?php endif; ?>
	</div>
</main>


<?php 
	get_footer();
?>