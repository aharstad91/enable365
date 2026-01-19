<?php
/**
 * E365 Block: Testimonial
 *
 * Display customer testimonials with optional logo, quote, CTA link, and author info.
 * Improved version with better centering, conditional logic, and mobile responsiveness.
 *
 * @package Enable365
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

$block_class = 'e365-testimonial';
$is_preview = e365_is_block_editor();

// Get all fields
$logo = get_field('logo');
$quote = get_field('quote') ?: '';
$cta_text = get_field('cta_text') ?: '';
$cta_link = get_field('cta_link') ?: '';
$avatar = get_field('avatar');
$author_name = get_field('author_name') ?: '';
$author_title = get_field('author_title') ?: '';

// Layout options
$layout = get_field('layout') ?: 'centered';
$background = get_field('background_style') ?: 'light';
$container_width = get_field('container_width') ?: 'narrow';
$show_quote_marks = get_field('show_quote_marks') !== false; // Default true

// Block ID
$block_id = !empty($block['anchor']) ? $block['anchor'] : $block_class . '-' . $block['id'];

// Determine if we have content
$has_logo = $logo && is_array($logo);
$has_quote = !empty(trim(strip_tags($quote)));
$has_cta = !empty($cta_text) && !empty($cta_link);
$has_avatar = $avatar && is_array($avatar);
$has_author = !empty($author_name);
$has_any_content = $has_logo || $has_quote || $has_author;

// Build wrapper classes
$wrapper_classes = [
    $block_class,
    'e365-block',
    'ani-clean',
];

// Background style
switch ($background) {
    case 'light':
        $wrapper_classes[] = 'bg-slate-50';
        break;
    case 'white':
        $wrapper_classes[] = 'bg-white border border-slate-200';
        break;
    case 'dark':
        $wrapper_classes[] = 'bg-slate-900 text-white';
        break;
    case 'brand':
        $wrapper_classes[] = 'bg-[#AA1010] text-white';
        break;
    case 'none':
        // No background
        break;
}

// Container width mapping
$container_width_classes = [
    'content' => 'max-w-[720px]',
    'narrow' => 'max-w-[960px]',
    'standard' => 'max-w-[1280px]',
    'wide' => 'max-w-[1440px]',
    'full' => 'max-w-full',
];

// Layout classes
$content_classes = [
    'e365-testimonial__content',
    $container_width_classes[$container_width] ?? 'max-w-[960px]',
    'mx-auto',
    'px-4',
    'sm:px-6',
    'lg:px-8',
];

if ($layout === 'centered') {
    $content_classes[] = 'text-center';
} else {
    $content_classes[] = 'text-left';
}

// Quote text classes
$quote_classes = [
    'e365-testimonial__quote',
    'text-lg',
    'sm:text-xl',
    'lg:text-2xl',
    'leading-relaxed',
    'lg:leading-relaxed',
    'font-medium',
];

if ($background === 'dark' || $background === 'brand') {
    $quote_classes[] = 'text-white';
} else {
    $quote_classes[] = 'text-slate-800';
}

// CTA link classes
$cta_classes = [
    'e365-testimonial__cta',
    'inline-block',
    'mt-4',
    'text-base',
    'lg:text-lg',
    'font-medium',
    'hover:underline',
];

if ($background === 'dark') {
    $cta_classes[] = 'text-red-400 hover:text-red-300';
} elseif ($background === 'brand') {
    $cta_classes[] = 'text-white/90 hover:text-white';
} else {
    $cta_classes[] = 'text-[#AA1010]';
}

// Author text classes
$author_name_classes = ['font-semibold'];
$author_title_classes = ['text-sm', 'lg:text-base'];

if ($background === 'dark' || $background === 'brand') {
    $author_name_classes[] = 'text-white';
    $author_title_classes[] = 'text-white/70';
} else {
    $author_name_classes[] = 'text-slate-900';
    $author_title_classes[] = 'text-slate-600';
}
?>

<div id="<?php echo esc_attr($block_id); ?>"
     class="<?php echo esc_attr(implode(' ', $wrapper_classes)); ?>"
     <?php if (!empty($block['className'])) echo 'data-custom-class="' . esc_attr($block['className']) . '"'; ?>>

    <?php if ($has_any_content): ?>
        <div class="<?php echo esc_attr(implode(' ', $content_classes)); ?> py-8 sm:py-12 lg:py-16">

            <?php // Logo ?>
            <?php if ($has_logo): ?>
                <figure class="e365-testimonial__logo mb-6 lg:mb-8 <?php echo $layout === 'centered' ? 'flex justify-center' : ''; ?>">
                    <img
                        src="<?php echo esc_url($logo['url']); ?>"
                        alt="<?php echo esc_attr($logo['alt'] ?: $author_name); ?>"
                        class="h-12 lg:h-16 w-auto object-contain"
                        loading="lazy"
                    >
                </figure>
            <?php endif; ?>

            <?php // Quote ?>
            <?php if ($has_quote): ?>
                <blockquote class="<?php echo esc_attr(implode(' ', $quote_classes)); ?>">
                    <?php if ($show_quote_marks): ?>
                        <span class="e365-testimonial__quote-text"><?php echo wp_kses_post($quote); ?></span>
                    <?php else: ?>
                        <?php echo wp_kses_post($quote); ?>
                    <?php endif; ?>
                </blockquote>

                <?php // CTA Link ?>
                <?php if ($has_cta): ?>
                    <a href="<?php echo esc_url($cta_link); ?>" class="<?php echo esc_attr(implode(' ', $cta_classes)); ?>">
                        <?php echo esc_html($cta_text); ?>
                    </a>
                <?php endif; ?>
            <?php endif; ?>

            <?php // Author section ?>
            <?php if ($has_author): ?>
                <div class="e365-testimonial__author mt-6 lg:mt-8 <?php echo $layout === 'centered' ? 'flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4' : 'flex items-center gap-4'; ?>">
                    <?php if ($has_avatar): ?>
                        <img
                            src="<?php echo esc_url($avatar['url']); ?>"
                            alt="<?php echo esc_attr($author_name); ?>"
                            class="w-14 h-14 lg:w-16 lg:h-16 rounded-full object-cover flex-shrink-0"
                            loading="lazy"
                        >
                    <?php endif; ?>
                    <div class="e365-testimonial__author-info <?php echo $layout === 'centered' && !$has_avatar ? '' : ($layout === 'centered' ? 'text-left' : ''); ?>">
                        <p class="<?php echo esc_attr(implode(' ', $author_name_classes)); ?> m-0">
                            <?php echo esc_html($author_name); ?>
                        </p>
                        <?php if (!empty($author_title)): ?>
                            <p class="<?php echo esc_attr(implode(' ', $author_title_classes)); ?> m-0">
                                <?php echo esc_html($author_title); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    <?php elseif ($is_preview): ?>
        <div class="e365-block-placeholder bg-slate-100 border-2 border-dashed border-slate-300 rounded-lg p-8 text-center text-slate-500 m-4">
            <p class="m-0 text-lg font-medium">E365 Testimonial</p>
            <p class="m-0 mt-2 text-sm">Legg til et sitat og forfatterinfo via sidefeltene til høyre.</p>
        </div>
    <?php endif; ?>
</div>
