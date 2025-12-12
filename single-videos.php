<?php
/**
 * Single template for Videos CPT
 * Displays full-width responsive YouTube embed with VideoObject schema markup
 */

get_header();

the_post();

// Get video meta
$youtube_url = get_post_meta(get_the_ID(), '_video_youtube_url', true);
$video_name = get_post_meta(get_the_ID(), '_video_name', true);
$video_description = get_post_meta(get_the_ID(), '_video_description', true);

// Fallback to post title/excerpt if schema fields not set
if (empty($video_name)) {
    $video_name = get_the_title();
}
if (empty($video_description)) {
    $video_description = get_the_excerpt();
}

?>

<main id="primary " class="container container-l pt-8 pb-16">
    <article <?php post_class('video-single'); ?>>
        
        <!-- Video Header -->
        <header class="max-w-4xl mx-auto  mb-8">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-slate-900 mb-4">
                <?php echo esc_html($video_name); ?>
            </h1>
            <?php if ($video_description): ?>
            <p class="text-lg sm:text-xl text-slate-600 mb-6">
                <?php echo esc_html($video_description); ?>
            </p>
            <?php endif; ?>
            
            <div class="flex items-center gap-4 text-sm text-slate-500">
                <time datetime="<?php echo get_the_date('Y-m-d'); ?>">
                    <?php echo get_the_date('j. M Y'); ?>
                </time>
            </div>
        </header>

        <!-- Video Embed - Full Width -->
        <section class="max-w-5xl mx-auto  mb-12">
            <?php if ($youtube_url && function_exists('enable365_video_embed')): ?>
                <div class="rounded-xl overflow-hidden shadow-lg">
                    <?php echo enable365_video_embed(); ?>
                </div>
            <?php else: ?>
                <div class="bg-slate-100 rounded-xl p-12 text-center">
                    <p class="text-slate-500">Ingen video er lagt til enda.</p>
                </div>
            <?php endif; ?>
        </section>

        <!-- Post Content (if any) -->
        <?php 
        $content = get_the_content();
        if (!empty(trim($content))): 
        ?>
        <section class="max-w-3xl mx-auto">
            <div class="prose prose-slate max-w-none prose-headings:scroll-mt-20 prose-h2:mt-12 prose-h2:text-slate-900 prose-h3:text-slate-900 prose-img:rounded-xl prose-img:border prose-img:border-slate-200">
                <?php the_content(); ?>
            </div>
        </section>
        <?php endif; ?>

    </article>
</main>

<?php get_footer(); ?>
