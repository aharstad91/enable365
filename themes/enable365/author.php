<?php
/**
 * Author template – Tailwind implementation matching the theme design
 * Shows author information and all their posts
 */

get_header();

$author = get_queried_object();
$first_name = get_the_author_meta('first_name', $author->ID);
$last_name = get_the_author_meta('last_name', $author->ID);
$author_name = trim( ($first_name || $last_name) ? "$first_name $last_name" : $author->display_name );
$author_bio = get_the_author_meta('description', $author->ID);
$author_avatar = get_avatar($author->ID, 160, '', '', ['class' => 'h-36 w-36 rounded-full']);

$author_website = get_the_author_meta('user_url', $author_id);

?>

<main id="primary" class="">
  <!-- Two-column layout: left = author info, right = posts list -->
  <section class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-10 sm:mt-14 mb-24">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-24 items-start">
      <!-- Left: Author card (sticky on large screens) -->
      <!-- On large screens we visually move the author sidebar to the right using order classes -->
      <!-- Make the aside itself sticky on large screens so it follows while scrolling -->
      <aside class="lg:col-span-1 lg:order-last lg:sticky lg:top-20 lg:self-start">
        <div class="bg-white/0">
          <div class="flex flex-col">
            <div class="mb-6">
              <?php echo $author_avatar; ?>
            </div>
            <div class="text-2xl font-semibold tracking-tight text-slate-900 leading-tight">
              <?php echo esc_html($author_name); ?>
			
				<?php echo '<p><a href="' . esc_url($author_website) . '" target="_blank">Website</a></p>';
?>

	
							
            </div>
            <?php if ($author_bio): ?>
              <p class="mt-3 text-sm text-slate-600">
                <?php echo esc_html($author_bio); ?>
              </p>
            <?php endif; ?>
          </div>
        </div>
      </aside>

  <!-- Right: Posts list -->
  <!-- On large screens the posts container should appear on the left -->
  <div class="lg:col-span-2 lg:order-first">
        <?php
          // Custom query: only 'blog' CPT posts by this author
          $paged = get_query_var('paged') ? absint( get_query_var('paged') ) : 1;
          $author_posts_args = [
            'post_type' => 'blog',
            'author' => $author->ID,
            'posts_per_page' => 10,
            'paged' => $paged,
          ];
          $author_query = new WP_Query( $author_posts_args );
        ?>

        <?php if ( $author_query->have_posts() ): ?>
          <div class="">
            <h2 class="text-2xl font-semibold text-slate-900 mb-8">
              Innlegg av <?php echo esc_html($author_name); ?>
            </h2>
            <div class="space-y-8">
              <?php while ( $author_query->have_posts() ): $author_query->the_post(); ?>
                <article class="group border-b border-slate-200 pb-8 last:border-b-0">
                  <div class="flex flex-col sm:flex-row gap-12">
                    <!-- Featured Image -->
                    <?php if (has_post_thumbnail()): ?>
                      <div class="sm:w-48 flex-shrink-0">
                        <a href="<?php the_permalink(); ?>" class="block">
                          <div class="aspect-[4/3] w-full overflow-hidden rounded-lg bg-slate-100">
                            <?php the_post_thumbnail('medium', ['class' => 'h-full w-full object-cover group-hover:scale-[1.02] transition']); ?>
                          </div>
                        </a>
                      </div>
                    <?php endif; ?>
                    
                    <!-- Content -->
                    <div class="flex-1">
                      <!-- Post Type & Date -->
                      <div class="flex items-center gap-3 text-sm text-slate-500 mb-2">
                        <span class="inline-flex items-center rounded-full border border-slate-200 px-2.5 py-1 text-xs">
                          <?php
                            $post_type_obj = get_post_type_object(get_post_type());
                            echo esc_html($post_type_obj ? $post_type_obj->labels->singular_name : 'Innlegg');
                          ?>
                        </span>
                        <span>•</span>
                        <span><?php echo get_the_date('j. M Y'); ?></span>
                      </div>
                      
                      <!-- Title -->
                      <h3 class="text-xl font-semibold text-slate-900 mb-3 group-hover:text-slate-700 transition">
                        <a href="<?php the_permalink(); ?>" class="hover:underline">
                          <?php the_title(); ?>
                        </a>
                      </h3>
                      
                      <!-- Excerpt -->
                      <p class="text-slate-600 line-clamp-3 mb-4">
                        <?php echo esc_html(get_the_excerpt()); ?>
                      </p>
                      
                      <!-- Categories -->
                      <?php $cats = get_the_category(); if($cats): ?>
                        <div class="flex flex-wrap items-center gap-2 mb-4">
                          <?php foreach(array_slice($cats, 0, 3) as $cat): ?>
                            <a href="<?php echo esc_url(get_category_link($cat)); ?>" class="inline-flex items-center rounded-full border border-slate-200 px-2.5 py-1 text-xs text-slate-600 hover:bg-slate-50">
                              <?php echo esc_html($cat->name); ?>
                            </a>
                          <?php endforeach; ?>
                        </div>
                      <?php endif; ?>
                      
                      <!-- Read More Link -->
                      <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-sm font-medium text-teal-700 hover:text-teal-800">
                        Les mer →
                      </a>
                    </div>
                  </div>
                </article>
              <?php endwhile; ?>
            </div>

            <!-- Pagination -->
            <div class="mt-12">
              <?php
                echo paginate_links([
                  'total' => $author_query->max_num_pages,
                  'current' => $paged,
                  'mid_size' => 2,
                  'prev_text' => '← Forrige',
                  'next_text' => 'Neste →',
                  'type' => 'list',
                ]);
              ?>
            </div>
          </div>
          <?php wp_reset_postdata(); ?>
        <?php else: ?>
          <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-2xl font-semibold text-slate-900 mb-4">
              Ingen publikasjoner enda
            </h2>
            <p class="text-slate-600">
              <?php echo esc_html($author_name); ?> har ikke publisert noe innhold enda.
            </p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>
</main>

<?php get_footer(); ?>
