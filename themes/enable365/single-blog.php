<?php
/**
 * Single post template – Tailwind implementation inspired by the Figma mock
 * Place as single.php or single-post.php in your theme. Requires Tailwind.
 */

get_header();

the_post();

// Helpers
$permalink   = get_permalink();
$title       = get_the_title();
$date        = get_the_date('j. M Y');
$author_id   = get_the_author_meta('ID');
$author_name = get_the_author();
$avatar      = get_avatar( $author_id, 40, '', '', ['class' => 'h-10 w-10 rounded-full'] );
$author_website = get_the_author_meta('user_url');


$reading_wpm = 220; // avg reading speed
$content_str = wp_strip_all_tags( get_the_content() );
$word_count  = str_word_count( $content_str );
$read_time   = max(1, ceil($word_count / $reading_wpm));


$subtitle    = '';
if ( function_exists('get_field') ) {
  $subtitle = (string) get_field('subtitle');
}
if ( ! $subtitle ) {
  $subtitle = has_excerpt() ? get_the_excerpt() : '';
}

?>

<?php
// Extract top-section-author block from content so we can render it full-width
$raw_post_content = get_post_field( 'post_content', get_the_ID() );
$blocks = function_exists('parse_blocks') ? parse_blocks( $raw_post_content ) : array();
$top_section_html = '';
$remaining_blocks = $blocks;
if ( ! empty( $blocks ) ) {
	foreach ( $blocks as $index => $block ) {
		if ( ! empty( $block['blockName'] ) && strpos( $block['blockName'], 'top-section-author' ) !== false ) {
			if ( function_exists( 'render_block' ) ) {
				$top_section_html = render_block( $block );
			} else {
				// fallback: serialize and render
				$top_section_html = function_exists('serialize_blocks') ? serialize_blocks( array( $block ) ) : '';
			}
			// remove this block from remaining
			array_splice( $remaining_blocks, $index, 1 );
			break;
		}
	}
}

// Serialize remaining blocks back to HTML for normal rendering
$remaining_content = '';
if ( ! empty( $remaining_blocks ) && function_exists('serialize_blocks') ) {
	$remaining_content = serialize_blocks( $remaining_blocks );
} else {
	$remaining_content = $raw_post_content;
}

?>

<main id="primary" class="p-t-4">
  <article <?php post_class('editor'); ?>>

	<?php if ( $top_section_html ): ?>
	  <!-- Full-width top section rendered from the block found in content -->
	  <div class="w-full">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
		  <?php echo $top_section_html; ?>
		</div>
	  </div>
	<?php endif; ?>

	<!-- Body / content -->
	<section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 sm:mt-16">
	  <div class="prose prose-slate max-w-none prose-headings:scroll-mt-20 prose-h2:mt-12 prose-h2:text-slate-900 prose-h3:text-slate-900 prose-img:rounded-xl prose-img:border prose-img:border-slate-200 prose-figcaption:text-slate-500 marker:text-slate-600">
		<?php echo apply_filters( 'the_content', $remaining_content ? $remaining_content : $raw_post_content ); ?>
	  </div>

	  <!-- Tags -->
	  <?php $tags = get_the_tags(); if ($tags): ?>
		<div class="mt-10 flex flex-wrap gap-2">
		  <?php foreach ($tags as $tag): ?>
			<a href="<?php echo esc_url(get_tag_link($tag)); ?>" class="inline-flex items-center rounded-full border border-slate-200 px-3 py-1 text-sm text-slate-700 hover:bg-slate-50">
			  <?php echo esc_html($tag->name); ?>
			</a>
		  <?php endforeach; ?>
		</div>
	  <?php endif; ?>
	</section>

	<!-- Latest posts -->
	<section class="container mx-auto max-w-7xl mt-16 sm:mt-20 mb-24">
	  <div class="flex items-center justify-between">
		<h2 class="text-lg font-semibold text-slate-900">Latest blog</h2>
		<a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="text-sm text-slate-600 hover:text-slate-900">Se alle</a>
	  </div>

	  <div class="mt-6 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
		<?php
		  $q = new WP_Query([
			'post_type'      => 'blog',
			'posts_per_page' => 3,
			'post__not_in'   => [get_the_ID()],
			'ignore_sticky_posts' => 1
		  ]);
		  if ( $q->have_posts() ):
			while ( $q->have_posts() ): $q->the_post();
		?>
		  <article class="group overflow-hidden rounded-2xl border border-slate-200 hover:shadow-sm transition">
			<a href="<?php the_permalink(); ?>" class="block">
			  <div class="aspect-[16/10] w-full overflow-hidden bg-slate-100">
				<?php if ( has_post_thumbnail() ) {
				  the_post_thumbnail('large', ['class' => 'h-full w-full object-cover group-hover:scale-[1.02] transition']);
				} ?>
			  </div>
			  <div class="p-4">
				<div class="flex flex-wrap items-center gap-2 text-xs text-slate-600">
				  <?php $cats = get_the_category(); if($cats): foreach(array_slice($cats,0,2) as $cat): ?>
					<span class="inline-flex items-center rounded-full border border-slate-200 px-2 py-0.5"><?php echo esc_html($cat->name); ?></span>
				  <?php endforeach; endif; ?>
				</div>
				<h3 class="mt-2 line-clamp-2 text-base font-medium text-slate-900 group-hover:underline"><?php the_title(); ?></h3>
				<p class="mt-1 line-clamp-2 text-sm text-slate-600"><?php echo esc_html( get_the_excerpt() ); ?></p>
				<span class="mt-3 inline-flex items-center text-sm font-medium" style="color: #ac1500;">Les mer →</span>
			  </div>
			</a>
		  </article>
		<?php endwhile; wp_reset_postdata(); endif; ?>
	  </div>
	</section>
  </article>
</main>

<?php get_footer(); ?>
