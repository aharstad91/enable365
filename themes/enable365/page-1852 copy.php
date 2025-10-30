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

<main class="container container-xl">
	<div>
		<?php the_content();?>
	</div>
	<ul class="categories-list">
		<?php foreach($terms as $term){ ?>
			<?php $term_link = get_term_link( $term );?>
			<li>
				<a href="<?php echo esc_url( $term_link );?>"><?php echo $term->name?> (<?php echo $term->count;?>)</a>
			</li>
		<?php } ?>    
	</ul>
	
	<?php 
	$args = array('post_type' => array('newsroom'), 'posts_per_page' => -1, 'orderby' => 'menu_order title', 'order'=>'ASC');
	?>
	<div class="grid-tests">
		<?php
		$news = new WP_Query( $args );
			while ( $news->have_posts() ) : $news->the_post();?>
			
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