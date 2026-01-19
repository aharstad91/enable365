<?php
/**
 * E365 Block: Logo Grid
 *
 * Display client/partner logos in a responsive grid.
 * Supports multiple layout styles and optional heading.
 *
 * @package Enable365
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

$block_class = 'e365-logo-grid';
$is_preview = e365_is_block_editor();

// Get settings
$logos = get_field('logos') ?: [];
$heading = get_field('heading') ?: '';
$subheading = get_field('subheading') ?: '';
$columns = get_field('columns') ?: 5;
$columns = intval($columns);
$logo_style = get_field('logo_style') ?: 'default';
$logo_size = get_field('logo_size') ?: 'md';
$alignment = get_field('alignment') ?: 'center';
$grayscale = get_field('grayscale') ?: false;
$hover_effect = get_field('hover_effect') ?: 'none';

// Block ID
$block_id = !empty($block['anchor']) ? $block['anchor'] : $block_class . '-' . $block['id'];

// Build classes
$wrapper_classes = [
    $block_class,
    'e365-block',
    'ani-clean',
];

if (!empty($block['className'])) {
    $wrapper_classes[] = $block['className'];
}

// Grid classes based on columns
$grid_classes = [
    'e365-logo-grid__grid',
    'grid',
    'gap-6',
    'lg:gap-8',
];

// Responsive column classes
switch ($columns) {
    case 3:
        $grid_classes[] = 'grid-cols-2 md:grid-cols-3';
        break;
    case 4:
        $grid_classes[] = 'grid-cols-2 md:grid-cols-4';
        break;
    case 5:
        $grid_classes[] = 'grid-cols-2 md:grid-cols-3 lg:grid-cols-5';
        break;
    case 6:
        $grid_classes[] = 'grid-cols-2 md:grid-cols-3 lg:grid-cols-6';
        break;
    default:
        $grid_classes[] = 'grid-cols-2 md:grid-cols-3 lg:grid-cols-5';
}

// Logo item classes
$logo_classes = [
    'e365-logo-grid__item',
    'flex',
    'items-center',
    'justify-center',
    'p-4',
];

// Logo size
switch ($logo_size) {
    case 'sm':
        $logo_classes[] = 'h-12 lg:h-16';
        break;
    case 'md':
        $logo_classes[] = 'h-16 lg:h-20';
        break;
    case 'lg':
        $logo_classes[] = 'h-20 lg:h-24';
        break;
}

// Style variations
if ($logo_style === 'bordered') {
    $logo_classes[] = 'border border-slate-200 rounded-lg bg-white';
}

// Grayscale
if ($grayscale) {
    $logo_classes[] = 'grayscale';
    if ($hover_effect === 'color') {
        $logo_classes[] = 'hover:grayscale-0 transition-all duration-300';
    }
}

// Hover effects
if ($hover_effect === 'scale') {
    $logo_classes[] = 'hover:scale-105 transition-transform duration-300';
} elseif ($hover_effect === 'opacity') {
    $logo_classes[] = 'opacity-70 hover:opacity-100 transition-opacity duration-300';
}

// Alignment
$alignment_class = 'text-' . $alignment;
?>

<div id="<?php echo esc_attr($block_id); ?>" class="<?php echo esc_attr(implode(' ', $wrapper_classes)); ?>">
    <?php if ($heading || $subheading): ?>
        <div class="e365-logo-grid__header mb-8 lg:mb-12 <?php echo esc_attr($alignment_class); ?>">
            <?php if ($heading): ?>
                <h2 class="text-xl lg:text-2xl font-semibold text-slate-800 mb-2"><?php echo esc_html($heading); ?></h2>
            <?php endif; ?>
            <?php if ($subheading): ?>
                <p class="text-slate-600"><?php echo esc_html($subheading); ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if ($logos): ?>
        <div class="<?php echo esc_attr(implode(' ', $grid_classes)); ?>">
            <?php foreach ($logos as $logo):
                $image = $logo['logo_image'] ?? null;
                $link = $logo['logo_link'] ?? '';
                $name = $logo['logo_name'] ?? '';

                if (!$image) continue;

                $img_html = wp_get_attachment_image(
                    $image['ID'],
                    'medium',
                    false,
                    [
                        'class' => 'max-h-full w-auto object-contain',
                        'alt' => $name ?: ($image['alt'] ?? ''),
                    ]
                );
            ?>
                <div class="<?php echo esc_attr(implode(' ', $logo_classes)); ?>">
                    <?php if ($link): ?>
                        <a href="<?php echo esc_url($link); ?>"
                           target="_blank"
                           rel="noopener noreferrer"
                           title="<?php echo esc_attr($name); ?>"
                           class="block h-full w-full flex items-center justify-center">
                            <?php echo $img_html; ?>
                        </a>
                    <?php else: ?>
                        <?php echo $img_html; ?>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php elseif ($is_preview): ?>
        <div class="e365-block-placeholder bg-slate-100 border-2 border-dashed border-slate-300 rounded-lg p-8 text-center text-slate-500">
            <p class="m-0 text-lg font-medium">E365 Logo Grid</p>
            <p class="m-0 mt-2 text-sm">Legg til logoer via sidefeltene til høyre.</p>
        </div>
    <?php endif; ?>
</div>
