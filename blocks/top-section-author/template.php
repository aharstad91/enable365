<?php
/**
 * Block Template: Top Section with Author
 */

$id = 'top-section-author-' . $block['id'];
if (!empty($block['anchor'])) {
        $id = $block['anchor'];
}
$className = 'top-section-author';
if (!empty($block['className'])) {
        $className .= ' ' . $block['className'];
}

$title = get_field('title');
$excerpt = get_field('excerpt');
$featured_image = get_field('featured_image');

// Post and author fallbacks
$post_obj = get_post();
$post_id = $post_obj ? $post_obj->ID : 0;
$author_id = $post_id ? get_post_field('post_author', $post_id) : 0;

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

$author_url = $author_id ? get_author_posts_url($author_id) : '#';
$avatar_url = $author_id ? get_avatar_url($author_id, ['size' => 80]) : '';

// Published date and reading time
$published = $post_id ? get_the_date('j. M Y', $post_id) : '';
$modified = $post_id ? get_the_modified_date('j. M Y', $post_id) : '';
$show_modified = $post_id && ($published !== $modified); // Only show if different from published
$content = $post_id ? get_post_field('post_content', $post_id) : '';
$word_count = $content ? str_word_count( wp_strip_all_tags( $content ) ) : 0;
$reading_minutes = $word_count ? max(1, round($word_count / 200)) : '';

// Prepare featured image output using core/image block with lightbox enabled
$featured_img_html = '';
if ($post_id && has_post_thumbnail($post_id)) {
    $thumbnail_id = get_post_thumbnail_id($post_id);
    $thumbnail_url = wp_get_attachment_image_url($thumbnail_id, 'full');
    $thumbnail_alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true) ?: get_the_title($thumbnail_id);
    
    if ($thumbnail_url) {
        // Use do_blocks() to properly render the core/image block with lightbox
        // This ensures the full WordPress block rendering pipeline is used,
        // including the lightbox Interactivity API directives
        $block_markup = sprintf(
            '<!-- wp:image {"id":%d,"sizeSlug":"full","linkDestination":"none","lightbox":{"enabled":true},"className":"featured-image-lightbox"} -->
<figure class="wp-block-image size-full featured-image-lightbox"><img src="%s" alt="%s" class="wp-image-%d"/></figure>
<!-- /wp:image -->',
            $thumbnail_id,
            esc_url($thumbnail_url),
            esc_attr($thumbnail_alt),
            $thumbnail_id
        );
        
        $featured_img_html = do_blocks($block_markup);
    }

    // Fallback: direct thumbnail if do_blocks not available or returned empty
    if (empty($featured_img_html)) {
        $featured_img_html = get_the_post_thumbnail($post_id, 'full', array('class' => 'w-full aspect-video object-cover wp-post-image'));
    }
}

?>

<div id="<?php echo esc_attr($id); ?>" class="ani-clean <?php echo esc_attr($className); ?>">
    <div class="flex flex-col lg:flex-row gap-12 lg:gap-24 items-center justify-center">
        <!-- Text column -->
        <div class="flex-1 max-w-lg lg:max-w-none">
            <!-- Categories -->
            <div class="flex flex-wrap items-center gap-2 text-xs sm:text-[13px] text-slate-600">
                <!-- categories can be inserted here if needed -->
            </div>

            <!-- Category chip -->
            <?php 
            // Get blog categories for this post
            $blog_categories = get_the_terms($post_id, 'blog-categories');
            if ($blog_categories && !is_wp_error($blog_categories)):
                $first_category = $blog_categories[0];
            ?>
            <div class="inline-block">
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                    <?php echo esc_html($first_category->name); ?>
                </span>
            </div>
            <?php endif; ?>

            <h1 class="mt-4 text-3xl sm:text-4xl lg:text-5xl font-semibold tracking-tight text-slate-900 leading-tight sm:leading-tight lg:leading-tight">
                <?php if ($title) { echo esc_html($title); } else { the_title(); } ?>
            </h1>

            <?php if ($excerpt): ?>
                <div class="mt-4 text-slate-700">
                    <div class="text-xl"><?php echo wp_kses_post($excerpt); ?></div>
                </div>
            <?php endif; ?>

        </div>

        <!-- Featured image column with lightbox support -->
        <div class="flex-1 max-w-lg lg:max-w-none lg:order-last featured-image-wrapper overflow-hidden rounded-2xl aspect-video">
            <?php 
            if ($featured_img_html): 
                echo $featured_img_html; // Outputs core/image block with lightbox directives
            endif; 
            ?>
        </div>
    </div>

    <div class="flex flex-col gap-6 lg:flex-row lg:justify-between lg:items-start lg:gap-8 mt-8 border-t border-slate-200 pt-6">
        <!-- Author / meta -->
        <div class="flex items-center gap-4 sm:gap-6 lg:gap-12">
            <div class="flex items-center gap-3">
                <?php if ($avatar_url): ?>
                    <img alt="<?php echo esc_attr($author_name); ?>" src="<?php echo esc_url($avatar_url); ?>" class="avatar avatar-40 photo h-10 w-10 rounded-full flex-shrink-0" height="40" width="40" decoding="async" />
                <?php endif; ?>

                <div class="text-sm min-w-0">
                    <div class="text-slate-900 font-medium">
                        <a href="<?php echo esc_url($author_url); ?>" class="text-base hover:text-slate-700 hover:underline">
                            <?php echo esc_html($author_name); ?>
                        </a>
                    </div>
                    <?php 
                    // Try different ways to get author title from ACF user fields
                    $author_title = get_field('title', 'user_' . $author_id) ?: get_field('author_title', 'user_' . $author_id) ?: get_user_meta($author_id, 'title', true);
                    if ($author_title): ?>
                        <div class="text-slate-500 font-medium text-sm">
                            <?php echo esc_html($author_title); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <dl class="text-slate-500 text-sm flex gap-6">
                <div class="flex flex-col gap-1">
                    <dt class="text-red-800 font-medium">Published on</dt>
                    <dd><?php echo esc_html($published); ?></dd>
                </div>
                <?php if ($show_modified): ?>
                <div class="flex flex-col gap-1">
                    <dt class="text-red-800 font-medium">Updated on</dt>
                    <dd><?php echo esc_html($modified); ?></dd>
                </div>
                <?php endif; ?>
            </dl>
        </div>
    <div>
        <!-- Share -->
      <div class="flex flex-wrap items-center gap-2 sm:gap-3">
        <?php
          $permalink = get_permalink($post_id);
          $url   = rawurlencode( $permalink );
          $titlee = rawurlencode( $title );
        ?>
            <button class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors" onclick="copyToClipboard(this, '<?php echo esc_js($permalink); ?>')" aria-label="Kopier lenke">
          <svg class="link-icon flex-shrink-0" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M10.5883 15.3034L9.40982 16.4819C7.78264 18.1091 5.14445 18.1091 3.51726 16.4819C1.89008 14.8547 1.89008 12.2165 3.51726 10.5893L4.69577 9.4108M15.3024 10.5893L16.4809 9.4108C18.1081 7.78361 18.1081 5.14542 16.4809 3.51824C14.8537 1.89106 12.2155 1.89106 10.5883 3.51824L9.40982 4.69675M7.08241 12.9167L12.9157 7.08337" stroke="#414651" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          <svg class="check-icon hidden flex-shrink-0" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M16.6667 5L7.50004 14.1667L3.33337 10" stroke="#059669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          <span class="font-medium hidden sm:inline">Link</span>
        </button>
                <a class="inline-flex items-center rounded-lg border border-slate-200 p-2 hover:bg-slate-50 flex-shrink-0" aria-label="Del på LinkedIn" target="_blank" rel="noopener" href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $url; ?>">
          <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g clip-path="url(#clip0_1471_8782)">
              <path d="M18.5236 0H1.47639C1.08483 0 0.709301 0.155548 0.432425 0.432425C0.155548 0.709301 0 1.08483 0 1.47639V18.5236C0 18.9152 0.155548 19.2907 0.432425 19.5676C0.709301 19.8445 1.08483 20 1.47639 20H18.5236C18.9152 20 19.2907 19.8445 19.5676 19.5676C19.8445 19.2907 20 18.9152 20 18.5236V1.47639C20 1.08483 19.8445 0.709301 19.5676 0.432425C19.2907 0.155548 18.9152 0 18.5236 0ZM5.96111 17.0375H2.95417V7.48611H5.96111V17.0375ZM4.45556 6.1625C4.11447 6.16058 3.7816 6.05766 3.49895 5.86674C3.21629 5.67582 2.99653 5.40544 2.8674 5.08974C2.73826 4.77404 2.70554 4.42716 2.77336 4.09288C2.84118 3.7586 3.0065 3.4519 3.24846 3.21148C3.49042 2.97107 3.79818 2.80772 4.13289 2.74205C4.4676 2.67638 4.81426 2.71133 5.12913 2.84249C5.44399 2.97365 5.71295 3.19514 5.90205 3.47901C6.09116 3.76288 6.19194 4.09641 6.19167 4.4375C6.19488 4.66586 6.15209 4.89253 6.06584 5.104C5.97959 5.31547 5.85165 5.50742 5.68964 5.66839C5.52763 5.82936 5.33487 5.95607 5.12285 6.04096C4.91083 6.12585 4.68389 6.16718 4.45556 6.1625ZM17.0444 17.0458H14.0389V11.8278C14.0389 10.2889 13.3847 9.81389 12.5403 9.81389C11.6486 9.81389 10.7736 10.4861 10.7736 11.8667V17.0458H7.76667V7.49306H10.6583V8.81667H10.6972C10.9875 8.22917 12.0042 7.225 13.5556 7.225C15.2333 7.225 17.0458 8.22083 17.0458 11.1375L17.0444 17.0458Z" fill="#A4A7AE"/>
            </g>
            <defs>
              <clipPath id="clip0_1471_8782">
                <rect width="20" height="20" fill="white"/>
              </clipPath>
            </defs>
          </svg>
        </a>
                <a class="inline-flex items-center rounded-lg border border-slate-200 p-2 hover:bg-slate-50 flex-shrink-0" aria-label="Del på Facebook" target="_blank" rel="noopener" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $url; ?>">
          <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g clip-path="url(#clip0_1471_8784)">
              <path d="M20 10C20 4.47715 15.5229 0 10 0C4.47715 0 0 4.47715 0 10C0 14.9912 3.65684 19.1283 8.4375 19.8785V12.8906H5.89844V10H8.4375V7.79688C8.4375 5.29063 9.93047 3.90625 12.2146 3.90625C13.3084 3.90625 14.4531 4.10156 14.4531 4.10156V6.5625H13.1922C11.95 6.5625 11.5625 7.3334 11.5625 8.125V10H14.3359L13.8926 12.8906H11.5625V19.8785C16.3432 19.1283 20 14.9912 20 10Z" fill="#A4A7AE"/>
              <path d="M13.8926 12.8906L14.3359 10H11.5625V8.125C11.5625 7.33418 11.95 6.5625 13.1922 6.5625H14.4531V4.10156C14.4531 4.10156 13.3088 3.90625 12.2146 3.90625C9.93047 3.90625 8.4375 5.29063 8.4375 7.79688V10H5.89844V12.8906H8.4375V19.8785C9.47287 20.0405 10.5271 20.0405 11.5625 19.8785V12.8906H13.8926Z" fill="white"/>
            </g>
            <defs>
              <clipPath id="clip0_1471_8784">
                <rect width="20" height="20" fill="white"/>
              </clipPath>
            </defs>
          </svg>
        </a>
        <a class="inline-flex items-center rounded-lg border border-slate-200 p-2 hover:bg-slate-50 flex-shrink-0" aria-label="Del på X" target="_blank" rel="noopener" href="https://twitter.com/intent/tweet?url=<?php echo $url; ?>&text=<?php echo $titlee; ?>">
          <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g clip-path="url(#clip0_1471_8787)">
              <path fill-rule="evenodd" clip-rule="evenodd" d="M13.2889 19.1663L8.66435 12.5748L2.87503 19.1663H0.425781L7.57772 11.0256L0.425781 0.833008H6.71407L11.0726 7.04552L16.5337 0.833008H18.9829L12.1629 8.59674L19.5772 19.1663H13.2889ZM16.0164 17.308H14.3674L3.93274 2.69134H5.5819L9.76107 8.54397L10.4838 9.55956L16.0164 17.308Z" fill="#A4A7AE"/>
            </g>
            <defs>
              <clipPath id="clip0_1471_8787">
                <rect width="20" height="20" fill="white"/>
              </clipPath>
            </defs>
          </svg>
        </a>
      </div>
            </div>

</div>

<!-- Divider line at full container width (1216px) -->
<div class="top-section-author-divider w-full border-b border-slate-200 mt-8 lg:mt-12"></div>

<script>
function copyToClipboard(button, text) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(function() {
            showSuccess(button);
        });
    } else {
        // Fallback
        var textArea = document.createElement("textarea");
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        showSuccess(button);
    }
}

function showSuccess(button) {
    var linkIcon = button.querySelector('.link-icon');
    var checkIcon = button.querySelector('.check-icon');
    
    // Hide link icon, show check icon
    linkIcon.classList.add('hidden');
    checkIcon.classList.remove('hidden');
    button.classList.add('bg-green-50', 'border-green-200', 'text-green-700');
    
    setTimeout(function() {
        // Show link icon, hide check icon
        linkIcon.classList.remove('hidden');
        checkIcon.classList.add('hidden');
        button.classList.remove('bg-green-50', 'border-green-200', 'text-green-700');
    }, 2000);
}
</script>
