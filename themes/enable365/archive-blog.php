<?php 
/**
 * Blog Archive Template - Modern Tailwind Design
 * Automatically used for /blog/ archive and blog post type archives
 */
get_header(); 
?>

<main id="primary" class="">
  <!-- Hero Section with Breadcrumb 
  <section class="w-full bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
      
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
            <span class="text-slate-900 font-medium" aria-current="page">
              <?php post_type_archive_title(); ?>
            </span>
          </li>
        </ol>
      </nav>

    
      <div class="max-w-3xl">
        <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-4">
          <?php post_type_archive_title(); ?>
        </h1>
        <?php if (get_the_archive_description()): ?>
          <div class="text-lg text-slate-600">
            <?php the_archive_description(); ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section> -->

  <!-- Blog Posts Grid -->
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20">
    
    <!-- Section Header -->
    <div class="flex items-center justify-between mb-8">
      <h2 class="text-2xl font-semibold text-slate-900">Alle blogginnlegg</h2>
    </div>

    <?php if (have_posts()): ?>
      
      <!-- Posts Grid -->
      <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
        <?php while (have_posts()): the_post(); ?>
          
          <article class="group overflow-hidden rounded-2xl border border-slate-200 bg-white hover:shadow-lg transition-all duration-300">
            <a href="<?php the_permalink(); ?>" class="block">
              
              <!-- Featured Image -->
              <div class="aspect-[16/10] w-full overflow-hidden bg-slate-100">
                <?php if (has_post_thumbnail()): ?>
                  <?php the_post_thumbnail('large', [
                    'class' => 'h-full w-full object-cover group-hover:scale-[1.02] transition-transform duration-300',
                    'loading' => 'lazy'
                  ]); ?>
                <?php else: ?>
                  <!-- Fallback placeholder -->
                  <div class="h-full w-full bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center">
                    <svg class="h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                  </div>
                <?php endif; ?>
              </div>
              
              <!-- Post Content -->
              <div class="p-6">
                
                <!-- Post Meta -->
                <div class="flex items-center gap-3 text-sm text-slate-500 mb-3">
                  <span class="inline-flex items-center rounded-full border border-slate-200 px-2.5 py-1 text-xs">
                    Blogg
                  </span>
                  <span>•</span>
                  <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date('j. M Y'); ?></time>
                  
                  <!-- Reading Time -->
                  <?php
                  $content = get_post_field('post_content', get_the_ID());
                  $word_count = str_word_count(wp_strip_all_tags($content));
                  $reading_time = max(1, round($word_count / 200));
                  ?>
                  <span>•</span>
                  <span><?php echo esc_html($reading_time); ?> min lesetid</span>
                </div>
                
                <!-- Title -->
                <h3 class="text-lg font-semibold text-slate-900 mb-3 group-hover:text-slate-700 transition line-clamp-2">
                  <?php the_title(); ?>
                </h3>
                
                <!-- Excerpt -->
                <p class="text-sm text-slate-600 line-clamp-2 mb-4 leading-relaxed">
                  <?php echo esc_html(get_the_excerpt()); ?>
                </p>
                
                <!-- Author -->
                <?php 
                $author_id = get_the_author_meta('ID'); 
                
                // Get author name - prioritize first name + last name over display_name
                $author_name = '';
                if ($author_id) {
                    $first_name = get_the_author_meta('first_name', $author_id);
                    $last_name = get_the_author_meta('last_name', $author_id);
                    
                    if ($first_name || $last_name) {
                        $author_name = trim($first_name . ' ' . $last_name);
                    } else {
                        $author_name = get_the_author_meta('display_name', $author_id);
                    }
                    
                    // Fallback if still empty
                    if (empty($author_name)) {
                        $author_name = get_the_author_meta('user_login', $author_id);
                    }
                }
                
                // Get author title from ACF user fields
                $author_title = get_field('title', 'user_' . $author_id) ?: get_field('author_title', 'user_' . $author_id) ?: get_user_meta($author_id, 'title', true);
                ?>
                <div class="flex items-center gap-3">
                  <?php echo get_avatar($author_id, 32, '', '', ['class' => 'h-8 w-8 rounded-full']); ?>
                  <div class="text-sm">
                    <div class="font-medium text-slate-900">
                      <?php echo esc_html($author_name); ?>
                    </div>
                    <?php if ($author_title): ?>
                      <div class="text-slate-500 text-xs">
                        <?php echo esc_html($author_title); ?>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </a>
          </article>
          
        <?php endwhile; ?>
      </div>
      
      <!-- Pagination -->
      <?php
      $pagination = paginate_links([
        'prev_text' => '← Forrige',
        'next_text' => 'Neste →',
        'type' => 'array'
      ]);
      
      if ($pagination): ?>
        <div class="mt-12 flex justify-center">
          <nav class="flex items-center gap-2" aria-label="Pagination">
            <?php foreach ($pagination as $page): ?>
              <?php echo $page; ?>
            <?php endforeach; ?>
          </nav>
        </div>
      <?php endif; ?>
      
    <?php else: ?>
      
      <!-- No Posts Found -->
      <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-slate-900">Ingen blogginnlegg funnet</h3>
        <p class="mt-1 text-sm text-slate-500">Det ser ut til at det ikke er publisert noen blogginnlegg ennå.</p>
      </div>
      
    <?php endif; ?>
  </section>
</main>

<?php get_footer(); ?>
