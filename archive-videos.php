<?php 
/**
 * Videos Archive Template - Grid View with App Filtering
 * 
 * This template fetches content from a page with slug "videos" to support WPML translations.
 * Create a page called "Videos" and its content will be displayed here.
 */
get_header(); 

// Get all video_app terms for filter buttons
$app_terms = get_terms(array(
    'taxonomy'   => 'video_app',
    'hide_empty' => true,
));

// Check if filtering by app
$current_app = get_queried_object();
$is_tax_page = is_tax('video_app');

// Get the "videos" page for translatable content (WPML compatible)
$videos_page = get_page_by_path('videos');
$page_title = $videos_page ? get_the_title($videos_page) : __('Videos', 'enable365');
$page_content = $videos_page ? apply_filters('the_content', $videos_page->post_content) : '';
$page_excerpt = $videos_page ? $videos_page->post_excerpt : '';
?>

<main id="primary" class="">
  <!-- Hero Section -->
  <section class="w-full bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
      
      <!-- Breadcrumb -->
      <nav class="mb-6" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-1 text-sm text-slate-600">
          <li class="flex items-center">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-slate-900 hover:underline transition-colors">
              Hjem
            </a>
          </li>
          <li class="flex items-center">
            <svg class="mx-2 h-4 w-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
            </svg>
            <?php if ($is_tax_page): ?>
              <a href="<?php echo get_post_type_archive_link('videos'); ?>" class="hover:text-slate-900 hover:underline transition-colors">
                Videos
              </a>
            <?php else: ?>
              <span class="text-slate-900 font-medium" aria-current="page">Videos</span>
            <?php endif; ?>
          </li>
          <?php if ($is_tax_page): ?>
          <li class="flex items-center">
            <svg class="mx-2 h-4 w-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
            </svg>
            <span class="text-slate-900 font-medium" aria-current="page">
              <?php echo esc_html($current_app->name); ?>
            </span>
          </li>
          <?php endif; ?>
        </ol>
      </nav>

      <!-- Title & Description -->
      <div class="max-w-3xl">
        <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-4">
          <?php if ($is_tax_page): ?>
            <?php echo esc_html($current_app->name); ?> Videos
          <?php else: ?>
            <?php echo esc_html($page_title); ?>
          <?php endif; ?>
        </h1>
        
        <?php if ($is_tax_page && $current_app->description): ?>
          <p class="text-lg text-slate-600"><?php echo esc_html($current_app->description); ?></p>
        <?php elseif ($page_content): ?>
          <div class="text-lg text-slate-600 prose prose-slate max-w-none">
            <?php echo $page_content; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- Filter & Grid Section -->
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20">
    
    <!-- App Filter Tags -->
    <?php if (!empty($app_terms) && !is_wp_error($app_terms)): ?>
    <div class="mb-10">
      <div class="flex flex-wrap items-center gap-3">
        
        <!-- All Videos -->
        <a href="<?php echo get_post_type_archive_link('videos'); ?>" 
           class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium transition-colors <?php echo !$is_tax_page ? 'bg-red-800 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'; ?>">
          Alle
        </a>
        
        <?php foreach ($app_terms as $term): ?>
          <a href="<?php echo get_term_link($term); ?>" 
             class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium transition-colors <?php echo ($is_tax_page && $current_app->term_id === $term->term_id) ? 'bg-red-800 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'; ?>">
            <?php echo esc_html($term->name); ?>
            <span class="ml-1.5 text-xs opacity-70">(<?php echo $term->count; ?>)</span>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <?php if (have_posts()): ?>
      
      <!-- Videos Grid -->
      <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
        <?php while (have_posts()): the_post(); 
          $youtube_url = get_post_meta(get_the_ID(), '_video_youtube_url', true);
          $thumbnail_url = get_post_meta(get_the_ID(), '_video_thumbnail_url', true);
          $video_apps = get_the_terms(get_the_ID(), 'video_app');
        ?>
          
          <article class="group overflow-hidden rounded-2xl border border-slate-200 bg-white hover:shadow-lg transition-all duration-300">
            <a href="<?php the_permalink(); ?>" class="block">
              
              <!-- Thumbnail with Play Button Overlay -->
              <div class="aspect-video w-full overflow-hidden bg-slate-100 relative">
                <?php if ($thumbnail_url): ?>
                  <img src="<?php echo esc_url($thumbnail_url); ?>" 
                       alt="<?php the_title_attribute(); ?>" 
                       class="h-full w-full object-cover group-hover:scale-[1.02] transition-transform duration-300"
                       loading="lazy">
                <?php elseif (has_post_thumbnail()): ?>
                  <?php the_post_thumbnail('large', [
                    'class' => 'h-full w-full object-cover group-hover:scale-[1.02] transition-transform duration-300',
                    'loading' => 'lazy'
                  ]); ?>
                <?php else: ?>
                  <div class="h-full w-full bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center">
                    <svg class="h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                  </div>
                <?php endif; ?>
                
                <!-- Play Button Overlay -->
                <div class="absolute inset-0 flex items-center justify-center bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                  <div class="w-16 h-16 rounded-full bg-red-600 flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white ml-1" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M8 5v14l11-7z"/>
                    </svg>
                  </div>
                </div>
              </div>
              
              <!-- Video Info -->
              <div class="p-6">
                
                <!-- App Tags -->
                <?php if ($video_apps && !is_wp_error($video_apps)): ?>
                <div class="flex flex-wrap gap-2 mb-3">
                  <?php foreach ($video_apps as $app): ?>
                    <span class="inline-flex items-center rounded-full bg-red-50 px-2.5 py-1 text-xs font-medium text-red-800">
                      <?php echo esc_html($app->name); ?>
                    </span>
                  <?php endforeach; ?>
                </div>
                <?php endif; ?>
                
                <!-- Title -->
                <h3 class="text-lg font-semibold text-slate-900 group-hover:text-red-800 transition-colors line-clamp-2 mb-2">
                  <?php the_title(); ?>
                </h3>
                
                <!-- Excerpt -->
                <?php if (has_excerpt()): ?>
                <p class="text-sm text-slate-600 line-clamp-2">
                  <?php echo get_the_excerpt(); ?>
                </p>
                <?php endif; ?>
                
                <!-- Date -->
                <div class="mt-4 text-sm text-slate-500">
                  <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date('j. M Y'); ?></time>
                </div>
              </div>
            </a>
          </article>
          
        <?php endwhile; ?>
      </div>

      <!-- Pagination -->
      <div class="mt-12 flex justify-center">
        <?php
        the_posts_pagination(array(
          'mid_size'  => 2,
          'prev_text' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>',
          'next_text' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>',
          'class'     => 'pagination',
        ));
        ?>
      </div>

    <?php else: ?>
      
      <!-- No Videos Found -->
      <div class="text-center py-16">
        <svg class="mx-auto h-16 w-16 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
        </svg>
        <h3 class="text-lg font-medium text-slate-900 mb-2">Ingen videoer funnet</h3>
        <p class="text-slate-600">
          <?php if ($is_tax_page): ?>
            Det finnes ingen videoer for <?php echo esc_html($current_app->name); ?> ennå.
          <?php else: ?>
            Det finnes ingen videoer ennå. Kom tilbake snart!
          <?php endif; ?>
        </p>
        <?php if ($is_tax_page): ?>
        <a href="<?php echo get_post_type_archive_link('videos'); ?>" class="inline-flex items-center mt-4 text-red-800 hover:text-red-900 font-medium">
          ← Se alle videoer
        </a>
        <?php endif; ?>
      </div>

    <?php endif; ?>
  </section>
</main>

<?php get_footer(); ?>
