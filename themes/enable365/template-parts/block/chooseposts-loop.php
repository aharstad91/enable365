<?php
/**
 * Block Name: Blog posts block
 *
 * This is the template that displays the Intro-section block.
 */
?>


<section class="post-grid">
	<?php $args = array(
		'post_type' => 'post',
		'posts_per_page' => 3 );
		
	$news = new WP_Query( $args );
		while ( $news->have_posts() ) : $news->the_post();?>
		<?php $featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full'); ?>
		
		
			<div class="">
				<figure>
					<a href="<?php the_permalink();?>" class="news-wrap post">
						<img src="<?php echo $featured_img_url;?>" />
					</a>
				</figure>
				<div>
					<a href="<?php the_permalink();?>" class="news-wrap post">
						<h2 class="title"><?php the_title();?></h2>
					</a>
					<?php the_excerpt();?>
				</div>
			</div>
						
		<?php endwhile; ?>
</section>
